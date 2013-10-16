<?php

namespace pho\Runnable;

use pho\Exception\ErrorException;
use pho\Exception\RunnableException;

abstract class Runnable
{
    public $context;

    public $exception;

    public function run()
    {
        if (is_callable($this->context)) {
            // Set the error handler for the spec
            set_error_handler([$this, 'handleError'], E_ALL);

            // Invoke the context while catching exceptions
            try {
                $this->context->__invoke();
            } catch (\Exception $exception) {
                $this->handleException($exception);
            }

            restore_error_handler();
        }
    }

    public function handleError($level, $string, $file = null, $line = null)
    {
        $this->exception = new ErrorException($level, $string, $file, $line);

        return true;
    }

    public function handleException($exception)
    {
        $this->exception = new RunnableException($exception);

        return true;
    }
}
