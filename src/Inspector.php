<?php

namespace DuskCrawler;

use Closure;
use Laravel\Dusk\Browser;
use React\Promise\Promise;
use Throwable;

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
     * @param  \Laravel\Dusk\Browser $browser
     * @return mixed
     */
    public function assert(Browser $browser)
    {
        return \call_user_func($this->action, $browser, $this);
    }

    /**
     * Abort and exit the assertion.
     */
    public function abort(string $abortedReason): bool
    {
        $this->failedException = new Exceptions\InspectionFailed($abortedReason);

        return true;
    }


    /**
     * Validate and throw if there is an exception.
     *
     * @return void
     */
    public function resolve(Browser $browser): Promise
    {
        return new Promise(function ($resolve, $reject) use ($browser) {
            if (\is_null($this->failedException)) {
                $resolve($browser);
            } else {
                $reject($this->failedException);
            }
        }, static function () use ($browser) {
            $browser->close();
        });
    }
}
