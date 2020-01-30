<?php

namespace DuskCrawler;

use Laravel\Dusk\Browser;
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
     * Abort exception.
     *
     * @var \Throwable|null
     */
    protected $exception;

    /**
     * Construct a new inspector.
     *
     * @param callable $action
     */
    public function __construct(callable $action)
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
        return \call_user_func($this->action, $browser, $inspector);
    }

    /**
     * Abort and exit the assertion.
     *
     * @param  \Throwable  $exception
     * @return bool
     */
    public function abort(Throwable $exception): bool
    {
        $this->exception = $exception;

        return true;
    }

    /**
     * Validate and throw if there is an exception.
     *
     * @return void
     */
    public function validate(): void
    {
        if (! \is_null($exception)) {
            Dusk::closeAll();

            throw $exception;
        }
    }
}
