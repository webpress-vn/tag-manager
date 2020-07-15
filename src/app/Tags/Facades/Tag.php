<?php

namespace VCComponent\Laravel\Tag\Tags\Facades;

use Illuminate\Support\Facades\Facade;

class Tag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'moduleTag.tag';
    }
}
