<?php

namespace VCComponent\Laravel\Tag\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Tag\Repositories\TagRepository;
use VCComponent\Laravel\Tag\Repositories\TagRepositoryEloquent;
use VCComponent\Laravel\Tag\Tags\Contracts\Tag as ContractsTag;
use VCComponent\Laravel\Tag\Tags\Tag;

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
        $this->publishes([
            __DIR__ . '/../../config/tag.php' => config_path('tag.php'),
        ], 'config');
    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TagRepository::class, TagRepositoryEloquent::class);

        $this->app->singleton('moduleTag.tag', function () {
            return new Tag();
        });

        $this->app->bind(ContractsTag::class, 'moduleTag.tag');
    }

    public function provides()
    {
        return [
            ContractsTag::class,
            'moduleTag.tag',
        ];
    }
}
