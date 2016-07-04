<?php
namespace Qsoft\Seo;

/**
 * @Author: thedv
 * @Date:   2016-07-01 17:57:19
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-04 11:55:15
 */
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Qsoft\Seo\SeoMiddleware;

class SeoServiceProvider extends ServiceProvider
{

    protected $namespace = 'Qsoft\Seo\Http\Controllers';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $this->publishes([
            __DIR__ . '/config.php' => config_path('qsoft_seo.php'),
        ], 'config');

        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware(SeoMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $router = $this->app['router'];
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'qsoft_seo'
        );
    }

}
