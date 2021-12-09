<?php

namespace VCComponent\Laravel\Tag\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Sluggable, SluggableScopeHelpers;
    protected $fillable = [
        'id',
        'name',
        'status',
    ];
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function posts()
    {
        return $this->morphedByMany('App\Entities\Post', 'taggable');
    }
    public function products()
    {
        return $this->morphedByMany('App\Entities\Product', 'taggable');
    }

    public function taggables() 
    {
        return $this->hasMany(Taggables::class);
    }
}
