<?php

namespace DuskCrawler\Tests\Feature;

use DuskCrawler\Tests\TestCase;
use Laravel\Dusk\Browser;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_has_the_proper_signature()
    {
        $this->assertTrue(Browser::hasMacro('inspectUsing'));
        $this->assertTrue(Browser::hasMacro('crawler'));
    }
}
