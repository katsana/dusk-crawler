<?php

namespace DuskCrawler\Tests\Feature\Console;

class PageCommandTest extends TestCase
{
    protected $files = [
        'app/Browser/Pages/Ping.php',
    ];

    /** @test */
    public function it_can_generate_page_file()
    {
        $this->artisan('dusk-crawler:page', ['name' => 'Ping'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Browser\Pages;',
            'use Laravel\Dusk\Browser;',
            'class Ping extends Page',
        ], 'app/Browser/Pages/Ping.php');
    }
}
