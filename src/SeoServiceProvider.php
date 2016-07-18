<?php
namespace Qsoft\Seo;

/**
 * @Author: thedv
 * @Date:   2016-07-01 17:57:19
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-18 16:16:03
 */
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Qsoft\Seo\Console\Commands\CachePage;
use Qsoft\Seo\Contracts\QsoftCache;
use Qsoft\Seo\Contracts\QsoftClawer;
use Qsoft\Seo\Http\Middleware\SeoMiddleware;

class SeoServiceProvider extends ServiceProvider
{

    protected $namespace = 'Qsoft\Seo\Http\Controllers';

    protected $commands = [
        CachePage::class,
    ];

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

        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(SeoMiddleware::class);

        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('qsoft:cache:sitemap')
        //         ->cron(config('qsoft_seo.cron'))
        //         ->timezone(config('qsoft_seo.time_zone'));
        // });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->commands($this->commands);
        $router = $this->app['router'];
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'qsoft_seo'
        );

        $this->app->instance('qsoft_clawer', new QsoftClawer);
        $this->app->instance('qsoft_cache', new QsoftCache);
    }

}
