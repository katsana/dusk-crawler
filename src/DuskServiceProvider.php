<?php

namespace DuskCrawler;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Orchestra\Canvas\Core\CommandsProvider;

class DuskServiceProvider extends ServiceProvider
{
    use CommandsProvider,
        Concerns\RegisterMacros;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBrowserMacros();
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
