<?php

namespace DuskCrawler;

use Laravel\Dusk\Browser;
use React\Promise\Deferred;
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
     */
    public function reject(Throwable $throwable): bool
    {
        $failedException = ! $throwable instanceof Exceptions\InspectionFailed
            ? Exceptions\InspectionFailed::from($throwable)
            : $throwable;

        $this->deferredPromise->reject($failedException);

        return true;
    }

    /**
     * Abort the promise.
     */
    public function abort(string $exception): bool
    {
        return $this->reject(
            Exceptions\InspectionFailed::make($exception)
        );
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
