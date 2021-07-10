<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use VCComponent\Laravel\Tag\Entities\Tag;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name'           => $faker->words(rand(4, 5), true),
        'status'    => 1,
    ];
});

