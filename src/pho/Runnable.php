<?php

namespace pho;

abstract class Runnable
{
    public $context;

    public $errors;

    public $exceptions;

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
        // TODO: Define an error handler class
        $this->errors[] = [$string, $file, $line];

        return true;
    }

    public function handleException($exception)
    {
        $this->exceptions[] = $exception;

        return true;
    }
}
