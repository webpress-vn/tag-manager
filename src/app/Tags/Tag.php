<?php

namespace VCComponent\Laravel\Tag\Tags;

use VCComponent\Laravel\Tag\Entities\Tag as EntitiesTag;
use VCComponent\Laravel\Tag\Tags\Contracts\Tag as ContractsTag;

class Tag implements ContractsTag
{

    public $entity;
    protected $limit;
    protected $column;
    protected $value;
    protected $id;
    protected $attributes = [];
    protected $direction;
    protected $relations;
    protected $tag_id;
    protected $tag_ids;

    public function __construct()
    {
        if (isset(config('tag.models')['tag'])) {
            $model        = config('tag.models.tag');
            $this->entity = new $model;
        } else {
            $this->entity = new EntitiesTag;
        }
    }
    public function withRelationPaginate($column = '', $value = '', $relations = 'products', $perPage = 5)
    {

        switch ($relations) {
            case "posts":
                $post = $this->entity->where($column, $value)->first();
                if ($post) {
                    return $post->posts()->paginate($perPage);
                }
                break;
            case "products":
                $product = $this->entity->where($column, $value)->first();
                if ($product) {
                    return $product->products()->paginate($perPage);
                }
                break;
            default:
                return $this;
                break;
        }
    }

    public function where($column, $value)
    {
        $query = $this->entity->where($column, $value)->get();

        return $query;
    }

    public function findOrFail($id)
    {
        return $this->entity->findOrFail($id);
    }

    public function toSql()
    {
        return $this->entity->toSql();
    }

    public function get()
    {
        return $this->entity->get();
    }

    public function paginate($perPage)
    {
        return $this->entity->paginate($perPage);
    }

    public function limit($value)
    {

        return $this->entity->limit($value);
    }

    public function orderBy($column, $direction = 'asc')
    {
        return $this->entity->orderBy($column, $direction);
    }

    public function with($relations)
    {
        $this->entity->with($relations);

        return $this;
    }

    public function first()
    {
        return $this->entity->first();
    }

    public function create(array $attributes = [])
    {
        return $this->entity->create($attributes);
    }

    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->entity->firstOrCreate($attributes, $values);
    }

    public function update(array $values)
    {
        return $this->entity->update($values);
    }

    public function delete()
    {
        return $this->entity->delete();
    }
}
