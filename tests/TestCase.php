<?php

namespace VCComponent\Laravel\Tag\Test;
use Cviebrock\EloquentSluggable\ServiceProvider;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Tag\Providers\TagServiceProvider;
use Dingo\Api\Provider\LaravelServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return HaiCS\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            TagServiceProvider::class,
            ServiceProvider::class,
            LaravelServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../src/database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('tag.namespace', 'tag-management');
        $app['config']->set('tag.models', [
            'tag' => \VCComponent\Laravel\Tag\Entities\Tag::class,
        ]);
        $app['config']->set('tag.transformers', [
            'tag' => \VCComponent\Laravel\Tag\Transformers\TagTransformer::class,
        ]);
        $app['config']->set('tag.auth_middleware', [
            'admin'    => [
                'middleware' => ''
            ],
            'frontend' => [
                'middleware' => ''
            ],
        ]);
        $app['config']->set('api', [
            'standardsTree'      => 'x',
            'subtype'            => '',
            'version'            => 'v1',
            'prefix'             => 'api',
            'domain'             => null,
            'name'               => null,
            'conditionalRequest' => true,
            'strict'             => false,
            'debug'              => true,
            'errorFormat'        => [
                'message'     => ':message',
                'errors'      => ':errors',
                'code'        => ':code',
                'status_code' => ':status_code',
                'debug'       => ':debug',
            ],
            'middleware'         => [
            ],
            'auth'               => [
            ],
            'throttling'         => [
            ],
            'transformer'        => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat'      => 'json',
            'formats'            => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions'     => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size'  => 2,
                ],
            ],
        ]);

    }
    public function assertValidation($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors"  => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
}
