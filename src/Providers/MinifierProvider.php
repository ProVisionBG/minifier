<?php

namespace ProVision\Minifier\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MinifierProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/provision_minifier.php' => config_path('provision_minifier.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/provision_minifier.php', 'provision_minifier'
        );

        if (Config::get('provision_minifier.autoload_middleware')) {
            $this->app['router']->pushMiddlewareToGroup('web', \ProVision\Minifier\Middleware\MinifierMiddleware::class);
        }

    }
}
