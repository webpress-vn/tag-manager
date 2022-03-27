<?php

namespace VCComponent\Laravel\Tag\Test\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Tag\Entities\Tag;
use VCComponent\Laravel\Tag\Test\TestCase;

class TagAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_create_tag_admin()
    {
        $data = factory(Tag::class)->make(['name' => ''])->toArray();
        $response = $this->json('POST', 'api/admin/tags', $data);
        $this->assertValidation($response, 'name', "The name field is required.");

        $data = factory(Tag::class)->make()->toArray();
        $response = $this->json('POST', 'api/admin/tags', $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => $data]);
        $this->assertDatabaseHas('tags', $data);

        $response = $this->json('POST', 'api/admin/tags', $data);
        $this->assertValidation($response, 'name', "The name has already been taken.");

    }
    /**
     * @test
     */
    public function should_update_tag_admin()
    {
        factory(Tag::class)->create(['name' => 'test tag']);

        $tag = factory(Tag::class)->make(['slug' => 'tag-slug']);
        $tag->save();
        unset($tag['updated_at']);
        unset($tag['created_at']);
        $id = $tag->id;
        $tag->name = 'test tag';
        $data = $tag->toArray();
        $response = $this->json('PUT', 'api/admin/tags/' . $id, $data);
        $this->assertValidation($response, 'name', "The name has already been taken.");

        $tag->name = 'update tag';
        $tag->status = 3;
        $tag->slug = 'update-slug';
        $data = $tag->toArray();
        $response = $this->json('PUT', 'api/admin/tags/' . $id, $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'slug' => 'tag-slug',
            ],
        ]);
        unset($data['slug']);
        $this->assertDatabaseHas('tags', $data);
    }
    /**
     * @test
     */
    public function should_soft_delete_tag_admin()
    {
        $tag = factory(Tag::class)->create()->toArray();
        unset($tag['updated_at']);
        unset($tag['created_at']);

        $this->assertDatabaseHas('tags', $tag);

        $response = $this->call('DELETE', 'api/admin/tags/' . $tag['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('tags', $tag);

    }
    /**
     * @test
     */
    public function should_get_tag_list_admin()
    {
        $tag = factory(Tag::class, 5)->create();

        $tag = $tag->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $listIds = array_column($tag, 'id');
        array_multisort($listIds, SORT_DESC, $tag);

        $response = $this->call('GET', 'api/admin/tags');

        $response->assertStatus(200);

        foreach ($tag as $item) {
            $this->assertDatabaseHas('tags', $item);
        }
    }
    /**
     * @test
     */
    public function should_get_tag_item_admin()
    {
        $tag = factory(Tag::class)->create();

        unset($tag['updated_at']);
        unset($tag['created_at']);

        $response = $this->call('GET', 'api/admin/tags/' . $tag->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $tag->name,
                'status' => $tag->status,
            ],
        ]);
    }
    /**
     * @test
     */
    public function should_get_tag_all_admin()
    {
        $listTag = [];
        for ($i = 0; $i < 5; $i++) {
            $tag = factory(Tag::class)->create()->toArray();
            unset($tag['updated_at']);
            unset($tag['created_at']);
            array_push($listTag, $tag);
        }

        $response = $this->call('GET', 'api/admin/tags');
        $response->assertStatus(200);
        /* sort by id */
        $listIds = array_column($listTag, 'id');
        array_multisort($listIds, SORT_DESC, $listTag);

        $response->assertJson(['data' => $listTag]);

        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
    }
    /**
     * @test
     */

    public function should_bulk_update_status_tag_by_admin()
    {
        $tags = factory(Tag::class, 5)->create();

        $tags = $tags->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $listIds = array_column($tags, 'id');

        $data = ['id' => $listIds, 'status' => 2];

        $response = $this->json('GET', 'api/admin/tags/all');

        $response->assertJsonFragment(['status' => 1]);

        $response = $this->json('PUT', 'api/admin/tags/status/bulk', $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $response = $this->json('GET', 'api/admin/tags/all');
        $response->assertJsonFragment(['status' => 2]);
    }
    /**
     * @test
     */

    public function should_update_status_tag_admin()
    {
        $tag = factory(tag::class)->create()->toArray();
        unset($tag['updated_at']);
        unset($tag['created_at']);

        $this->assertDatabaseHas('tags', $tag);

        $data = ['status' => 2];
        $response = $this->json('PUT', 'api/admin/tags/status/' . $tag['id'], $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $response = $this->json('GET', 'api/admin/tags/' . $tag['id']);

        $response->assertJson(['data' => $data]);

    }

}
