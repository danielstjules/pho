<?php

namespace pho\Exception;

class ExpectationException extends \Exception
{
    /**
     * Creates an ExpectationException, thrown by an Expectation when a match
     * fails. The file and line are set to the calling line of the spec.
     *
     * @param string $message The exception's message
     */
    public function __construct($message)
    {
        parent::__construct($message);

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $class = 'pho\Expectation\Expectation';
        $pos = -1;

        // Search for the last call in pho\Expectation\Expectation
        for ($i = 0; $i < min(count($stack), 8); $i++) {
            if (isset($stack[$i]['class']) && $stack[$i]['class'] === $class) {
                $pos = $i;
            }
        }

        if ($pos > -1 && isset($stack[$pos]['file']) && isset($stack[$pos]['line'])) {
            $this->file = $stack[$pos]['file'];
            $this->line = $stack[$pos]['line'];
        }
    }

    /**
     * Returns a string containing the expectation failure message.
     *
     * @return string A human readable description of the exception
     */
    public function __toString()
    {
        return "{$this->file}:{$this->line}\n$this->message";
    }
}