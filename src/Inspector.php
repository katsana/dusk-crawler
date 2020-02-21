<?php

namespace DuskCrawler;

use Laravel\Dusk\Browser;
use React\Promise\Deferred;
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
     * The deferred promise.
     *
     * @var \React\Promise\Deferred
     */
    protected $deferredPromise;

    /**
     * Construct a new inspector.
     */
    public function __construct(callable $action)
    {
        $this->action = $action;
        $this->deferredPromise = new Deferred();
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
     * Resolve the promise.
     */
    public function resolve(): bool
    {
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
     * Abort the promise.
     *
     * @param \Throwable|string $exception
     *
     * @deprecated v0.1.0
     * @see static::reject()
     */
    public function abort($exception): bool
    {
        return $this->reject($exception);
    }

    /**
     * Return resolved promise.
     */
    public function promise(Browser $browser): Promise
    {
        $promise = $this->deferredPromise->promise();

        $this->deferredPromise->resolve($browser);

        return $promise;
    }
}
