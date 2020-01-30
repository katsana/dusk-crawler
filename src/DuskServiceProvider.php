<?php

namespace DuskCrawler;

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
        Browser::macro('waitUsingInspect', function ($seconds, Inspector $inspector) {
            return \tap($this->waitUntil($seconds, 100, function () use ($inspector) {
                return $inspector->assert($this);
            }), static function ($browser) use ($inspector) {
                $inspector->validate();
            });
        });

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
        Browser::$storeScreenshotsAt = \storage_path('app/Browser/screenshots');
        Browser::$storeConsoleLogAt = \storage_path('logs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ComponentCommand::class,
                Console\PageCommand::class,
            ]);
        }
    }
}
