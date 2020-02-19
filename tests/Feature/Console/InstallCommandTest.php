<?php

namespace DuskCrawler\Tests\Feature\Console;

class InstallCommandTest extends TestCase
{
    protected $files = [
        'app/Browser/Pages/Page.php',
    ];

    /** @test */
    public function it_can_generate_install_files()
    {
        $this->artisan('dusk-crawler:install')
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Browser\Pages;',
            'use Laravel\Dusk\Page as BasePage;',
            'abstract class Page extends BasePage',
        ], 'app/Browser/Pages/Page.php');
    }
}
