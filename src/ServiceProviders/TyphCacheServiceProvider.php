<?php

namespace SaliBhdr\TyphoonCache\ServiceProviders;

use SaliBhdr\TyphoonCache\Observers\ModelObserver;
use Illuminate\Support\ServiceProvider;
use SaliBhdr\TyphoonCache\TyphCache;

class TyphCacheServiceProvider extends ServiceProvider
{

    protected $listen = [
        \SaliBhdr\TyphoonCache\Events\ModelCacheEvent::class => [
            \SaliBhdr\TyphoonCache\Listeners\ModelCacheListener::class,
        ],
        \SaliBhdr\TyphoonCache\Events\ModelDeleteEvent::class => [
            \SaliBhdr\TyphoonCache\Listeners\ModelDeleteListener::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__ . '/../../config/typhoon-cache.php');

        $this->mergeConfigFrom($path, 'typhoon-cache');

        $config = typhConfig();

        if ($config['cache-method'] == TyphCache::dispatcherEventMethod)
            $this->bootEventListeners();
        elseif ($config['cache-method'] == TyphCache::observerMethod) {
            $this->bootObserver($config);
        }
    }

    /**
     * boot event listeners for model
     */
    protected function bootEventListeners(): void
    {
        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * boot event listeners for model
     * @param null $config
     */
    protected function bootObserver($config = null): void
    {
        if (is_null($config)) {
            $config = config('typhoon-cache');
        }

        foreach ($config['models'] as $model => $value) {
            $model::observe(ModelObserver::class);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();

        $this->app->bind('TyphoonCache', function () {
            return new TyphCache;
        });

        $this->app->middleware([
            \SaliBhdr\TyphoonCache\Middleware\TyphCacheMiddleware::class
        ]);

        $this->publishes([
            __DIR__ . '/../../config/typhoon-cache.php' => $this->app['path.config'] . DIRECTORY_SEPARATOR . 'typhoon-cache.php',
        ], 'typhoon-cache');
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        // Load the helpers in app/Http/helpers.php
        if (file_exists($file = __DIR__.DIRECTORY_SEPARATOR.'..'. DIRECTORY_SEPARATOR .'helpers.php'))
        {
            require $file;
        }
    }


}
