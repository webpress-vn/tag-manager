<?php

namespace VCComponent\Laravel\Tag\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Tag\Repositories\TagRepository;
use VCComponent\Laravel\Tag\Repositories\TagRepositoryEloquent;

class TagServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TagRepository::class, TagRepositoryEloquent::class);
    }
}
