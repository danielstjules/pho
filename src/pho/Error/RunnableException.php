<?php

namespace pho\Error;

class RunnableException extends Error
{
    public function __construct($exception)
    {
        parent::__construct(get_class($exception), $exception->getMessage(),
            $exception->getFile(), $exception->getLine());
    }
}