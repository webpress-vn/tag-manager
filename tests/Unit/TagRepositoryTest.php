<?php

namespace VCComponent\Laravel\Tag\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use VCComponent\Laravel\Tag\Entities\Tag;
use VCComponent\Laravel\Tag\Repositories\TagRepositoryEloquent;
use VCComponent\Laravel\Tag\Test\TestCase;

class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_list_tags_by_reposotory_function()
    {
        $tag_repository = app(TagRepositoryEloquent::class);
        $date_tag = factory(Tag::class, 3)->create()->sortByDesc('created_at');

        $tag = $tag_repository->getListTags();
        $tag_number = $tag_repository->getListTags(3);

        $this->assertTagsEqualDatas($tag, $date_tag);
        $this->assertTagsEqualDatas($tag_number, $date_tag);

    }

    /**
     * @test
     */
    public function can_get_list_paginated_tags_by_reposotory_function()
    {
        $tag_repository = app(TagRepositoryEloquent::class);

        $date_tag = factory(Tag::class, 3)->create()->sortByDesc('created_at');

        $tag_paginated = $tag_repository->getListPaginatedTags(3);

        $this->assertTrue($tag_paginated instanceof LengthAwarePaginator);
        $this->assertTagsEqualDatas($tag_paginated, $date_tag);

    }

    protected function assertTagsEqualDatas($tag, $datas)
    {
        $this->assertEquals($tag->pluck('name'), $datas->pluck('name'));
    }

}
