<?php

namespace VCComponent\Laravel\Tag\Entities;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\Tag\Traits\HasTagsTraits;

class Taggables extends Model
{


    protected $fillable = [
        'taggable_id',
        'taggable_type',
    ];
}
