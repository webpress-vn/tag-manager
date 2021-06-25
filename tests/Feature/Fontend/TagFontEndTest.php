<?php

namespace VCComponent\Laravel\Tag\Test\Feature\Fontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Tag\Test\TestCase;
use VCComponent\Laravel\Tag\Entities\Tag;


class TagFontEndTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function should_get_tag_list_fontend()
    {
        $tag = factory(Tag::class, 5)->create();

        $tag = $tag->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();

        $listIds = array_column($tag, 'id');
        array_multisort($listIds, SORT_DESC, $tag);

        $response = $this->call('GET', 'api/tags/all');

        $response->assertStatus(200);

        foreach ($tag as $item) {
            $this->assertDatabaseHas('tags', $item);
        }
    }

    /**
     * @test
     */
    public function should_get_list_tags_with_paginate_frontend_router()
    {
        $listTag = [];
        for ($i = 0; $i < 5; $i++) {
            $tag = factory(Tag::class)->create()->toArray();
            unset($tag['updated_at']);
            unset($tag['created_at']);
            array_push($listTag, $tag);
        }

        $response = $this->call('GET', 'api/tags');
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
    public function should_get_tag_item_fontend()
    {
        $tag = factory(Tag::class)->create();

        unset($tag['updated_at']);
        unset($tag['created_at']);

        $response = $this->call('GET', 'api/tags/' . $tag->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name'       => $tag->name,
                'status' => $tag->status,
            ],
        ]);
    }

}
