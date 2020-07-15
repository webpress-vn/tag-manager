<?php

return [

    'namespace'       => env('TAG_COMPONENT_NAMESPACE', 'tag-management'),

    'models'          => [
        'tag' => VCComponent\Laravel\Tag\Entities\Tag::class,
    ],

    'transformers'    => [
        'post' => VCComponent\Laravel\Tag\Transformers\TagTransformer::class,
    ],

    'auth_middleware' => [
        'admin'    => [
            'middleware' => '',
            'except'     => [],
        ],
        'frontend' => [
            'middleware' => '',
            'except'     => [],
        ],
    ],

];
