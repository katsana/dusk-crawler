<?php

namespace DuskCrawler\Tests\Feature\Console;

abstract class TestCase extends \Orchestra\Canvas\Core\Testing\TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Orchestra\Canvas\Core\LaravelServiceProvider',
            'DuskCrawler\DuskServiceProvider',
        ];
    }
}
