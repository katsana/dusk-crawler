<?php

namespace DuskCrawler;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;
use Orchestra\Canvas\Core\CommandsProvider;

class DuskServiceProvider extends ServiceProvider
{
    use CommandsProvider;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Browser::macro('waitUsingInspect', function ($seconds, $action) {
            $inspector = $action instanceof Inspector ? $action : new Inspector($action);

            return $inspector->promise(
                $this->waitUsing($seconds, 100, function () use ($inspector) {
                    return $inspector->assert($this);
                })
            );
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
            $preset = $this->presetForLaravel($this->app);

            Artisan::starting(static function ($artisan) use ($preset) {
                $artisan->add(new Console\InstallCommand());
                $artisan->add(new Console\ComponentCommand($preset));
                $artisan->add(new Console\PageCommand($preset));
            });
        }
    }
}
