<?php

namespace DuskCrawler;

use Closure;
use Laravel\Dusk\Browser;
use React\Promise\Promise;

class Inspector
{
    /**
     * Callable inspector action.
     *
     * @var callable
     */
    protected $action;

    /**
     * Aborted exception.
     *
     * @var \DuskCrawler\Exceptions\InspectionFailed|null
     */
    protected $failedException;

    /**
     * Construct a new inspector.
     *
     * @param callable $action
     */
    public function __construct(Closure $action)
    {
        $this->action = $action;
    }

    /**
     * Assert using the browser.
     *
     * @return mixed
     */
    public function assert(Browser $browser)
    {
        return \call_user_func($this->action, $browser, $this);
    }

    /**
     * Abort and exit the assertion.
     *
     * @param \Throwable|string $exception
     */
    public function abort($exception): bool
    {
        $this->failedException = \is_string($exception)
            ? Exceptions\InspectionFailed::make($exception)
            : Exceptions\InspectionFailed::from($exception);

        return true;
    }

    /**
     * Validate and throw if there is an exception.
     *
     * @return void
     */
    public function promise(Browser $browser): Promise
    {
        return new Promise(function ($resolve, $reject) use ($browser) {
            if (! \is_null($this->failedException)) {
                $reject($this->failedException);
            }

            $resolve($browser);
        }, static function () use ($browser) {
            $browser->close();
        });
    }
}
