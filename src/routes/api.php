<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {

        $api->put('tags/status/bulk', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Admin\TagController@bulkUpdateStatus');
        $api->get('tags/all', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Admin\TagController@list');
        $api->put('tags/status/{id}', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Admin\TagController@updateStatus');
        $api->resource('tags', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Admin\TagController');

    });
    $api->get('tags/all', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Frontend\TagController@list');
    $api->resource('tags', 'VCComponent\Laravel\Tag\Http\Controllers\Api\Frontend\TagController');
});
