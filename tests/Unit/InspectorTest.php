<?php

namespace DuskCrawler\Tests\Unit;

use DuskCrawler\Inspector;
use Laravel\Dusk\Browser;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use React\Promise\Promise;

class InspectorTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_assert_success_path()
    {
        $browser = m::mock(Browser::class);
        $inspector = new Inspector(function () {
            return true;
        });

        $this->assertTrue($inspector->assert($browser));
        $promise = $inspector->promise($browser);

        $this->assertInstanceOf(Promise::class, $promise);
    }

    /** @test */
    public function it_can_assert_abort_path()
    {
        $this->expectException('DuskCrawler\Exceptions\InspectionFailed');
        $this->expectExceptionMessage('Foo');

        $browser = m::mock(Browser::class);
        $inspector = new Inspector(function ($browser, $inspector) {
            return $inspector->reject('Foo');
        });

        $this->assertTrue($inspector->assert($browser));
        $inspector->promise($browser)->done();
    }

    /** @test */
    public function it_can_assert_abort_path_as_exception()
    {
        $this->expectException('DuskCrawler\Exceptions\InspectionFailed');
        $this->expectExceptionMessage('Foobar');

        $browser = m::mock(Browser::class);
        $inspector = new Inspector(function ($browser, $inspector) {
            return $inspector->reject(new \RuntimeException('Foobar'));
        });

        $this->assertTrue($inspector->assert($browser));
        $inspector->promise($browser)->done();
    }
}
