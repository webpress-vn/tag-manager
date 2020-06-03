<?php

namespace VCComponent\Laravel\Tag\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Repository.
 */
interface TagRepository extends RepositoryInterface
{
    public function findById($request,$id);
}
