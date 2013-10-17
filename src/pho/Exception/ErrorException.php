<?php

namespace pho\Exception;

use pho\Exception\Exception;

class ErrorException extends Exception
{
    private static $errorConstants = [
        E_ERROR             => 'E_ERROR',
        E_WARNING           => 'E_WARNING',
        E_PARSE             => 'E_PARSE',
        E_NOTICE            => 'E_NOTICE',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    ];

    /**
     * Creates an ErrorException, given the same parameters used by an error
     * handler used with set_error_handler(). The class only handles the default
     * PHP constant error levels.
     *
     * @param int    $level  The error level corresponding to the PHP error
     * @param string $string Error message itself
     * @param string $file   The name of the file from which the error was raised
     * @param int    $line   The line number from which the error was raised
     */
    public function __construct($level, $string, $file = null, $line = null)
    {
        parent::__construct($string, 0);

        $this->file = $file;
        $this->line = $line;
        $this->type = self::$errorConstants[$level];
    }
}