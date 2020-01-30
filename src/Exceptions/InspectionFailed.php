<?php

namespace DuskCrawler\Exceptions;

class InspectionFailed extends \RuntimeException
{
    /**
     * Make an exception a message.
     *
     * @param  string $message
     * @return static
     */
    public static function make(string $message)
    {
        return new static($message);
    }

    /**
     * Make an exception from another exception.
     *
     * @param  \Throwable $exception
     * @return static
     */
    public static function from(\Throwable $exception)
    {
        return new static($exception->getMessage(), 0, $exception);
    }
}
