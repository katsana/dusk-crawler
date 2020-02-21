<?php

namespace DuskCrawler;

use Laravel\Dusk\Browser;
use React\Promise\Promise;
use React\Promise\Deferred;

class Inspector
{
    /**
     * Callable inspector action.
     *
     * @var callable
     */
    protected $action;

    /**
     * The deferred promise.
     *
     * @var \React\Promise\Deferred
     */
    protected $deferredPromise;

    /**
     * Browser implementation.
     *
     * @var \Laravel\Dusk\Browser|null
     */
    protected $browser;

    /**
     * Construct a new inspector.
     *
     * @param callable $action
     */
    public function __construct(callable $action)
    {
        $this->action = $action;
        $this->deferredPromise = new Deferred();
    }

    /**
     * Set browser instance.
     *
     * @return $this
     */
    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Assert using the browser.
     *
     * @return mixed
     */
    public function assert()
    {
        return \call_user_func($this->action, $this->browser, $this);
    }

    /**
     * Resolve the promise.
     *
     * @return bool
     */
    public function resolve(): bool
    {
        $this->deferredPromise->resolve($this->browser);

        return true;
    }

    /**
     * Reject the promise.
     *
     * @param \Throwable|string $exception
     */
    public function reject($exception): bool
    {
        $failedException = \is_string($exception)
            ? Exceptions\InspectionFailed::make($exception)
            : Exceptions\InspectionFailed::from($exception);

        $this->deferredPromise->reject($failedException);

        return true;
    }

    /**
     * Return resolved promise.
     */
    public function promise(): Promise
    {
        return $this->deferredPromise->promise();
    }
}
