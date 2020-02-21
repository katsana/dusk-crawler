<?php

namespace DuskCrawler\Tests\Feature\Console;

class ComponentCommandTest extends TestCase
{
    protected $files = [
        'app/Browser/Components/Ping.php',
    ];

    /** @test */
    public function it_can_generate_component_file()
    {
        $this->artisan('dusk-crawler:component', ['name' => 'Ping'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Browser\Components;',
            'use Laravel\Dusk\Browser;',
            'use Laravel\Dusk\Component as BaseComponent;',
            'class Ping extends BaseComponent',
        ], 'app/Browser/Components/Ping.php');
    }
}
