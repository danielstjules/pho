<?php

namespace pho\Runnable;

use pho\Exception\ExpectationException;
use pho\Exception\ErrorException;
use pho\Exception\RunnableException;

abstract class Runnable
{
    public $exception;

    protected $closure;

    protected $suite;

    /**
     * Invokes a Runnable object's $closure, setting an error handler and
     * catching all uncaught exceptions.
     */
    public function run()
    {
        if (is_callable($this->closure)) {
            // Set the error handler for the spec
            set_error_handler([$this, 'handleError'], E_ALL);

            // Invoke the closure while catching exceptions
            try {
                $this->closure->__invoke();
            } catch (ExpectationException $exception) {
                $this->exception = $exception;
            } catch (\Exception $exception) {
                $this->handleException($exception);
            }

            restore_error_handler();
        }
    }

    /**
     * An error handler to be used by set_error_handler(). Creates a custom
     * ErrorException, and sets the objects $exception property.
     *
     * @param int    $level  The error level corresponding to the PHP error
     * @param string $string Error message itself
     * @param string $file   The name of the file from which the error was raised
     * @param int    $line   The line number from which the error was raised
     */
    public function handleError($level, $string, $file = null, $line = null)
    {
        $this->exception = new ErrorException($level, $string, $file, $line);
    }

    /**
     * An exception handler to be used when calling Runnable::run(). Creates a
     * custom RunnableException, corresponding to an uncaught exception in a
     * test, and sets the objects $exception property.
     *
     * @param \Exception $exception The uncaught exception
     */
    public function handleException(\Exception $exception)
    {
        $this->exception = new RunnableException($exception);
    }
}
