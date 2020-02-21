<?php

namespace DuskCrawler\Concerns;

use DuskCrawler\Inspector;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;

trait RegisterMacros
{
    /**
     * Register browser macros.
     */
    protected function registerBrowserMacros(): void
    {
        Browser::macro('inspectUsing', function ($seconds, $action) {
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
}
