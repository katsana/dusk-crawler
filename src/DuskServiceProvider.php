<?php

namespace DuskCrawler;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use React\Promise\Promise;
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
            $browser = $this;

            $browser->waitUntil($seconds, 100, static function () use ($browser, $inspector) {
                return $inspector->assert($browser);
            });

            return new Promise(function ($resolve, $reject) use ($browser, $inspector) {
                try {
                    $inspector->validate();
                    $resolve($browser);
                } catch (Throwable $e) {
                    $reject($e);
                }
            }, static function () {
                Dusk::closeAll();
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
