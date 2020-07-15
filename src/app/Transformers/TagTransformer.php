<?php

namespace VCComponent\Laravel\Tag\Transformers;

use App\Entities\Post;
use App\Transformers\PostTransformer;
use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Product\Transformers\ProductTransformer;
use VCComponent\Laravel\Tag\Entities\Tag;

class TagTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'posts',
        'products',
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'slug'       => $model->slug,
            'status'     => (int) $model->status,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includePosts(Post $model)
    {
        return $this->collection($model->Posts, new PostTransformer());
    }
    public function includeProducts(Post $model)
    {
        return $this->collection($model->products, new ProductTransformer());
    }
}
