<?php

namespace Laravie\DuskCrawler;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Browser::macro('crawler', function () {
            return new Crawler(
                $this->driver->getPageSource() ?? '',
                $this->driver->getCurrentURL() ?? ''
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ComponentCommand::class,
                Console\PageCommand::class,
            ]);
        }
    }
}
