<?php

namespace VCComponent\Laravel\Tag\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Repository.
 */
interface TagRepository extends RepositoryInterface
{
    public function findById($request,$id);

    public function getListTags($number = null);
    public function getListPaginatedTags($per_page);

    public function getListTranslatableTags($number = null);
    public function getListPaginatedTranslatableTags($per_page);
}
