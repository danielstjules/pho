<?php

namespace pho\Exception;

abstract class Exception extends \Exception
{
    protected $type;

    /**
     * Returns a string containing the Exception type, message, filename and
     * line in human readable form for use by Reporters and the command line
     * runner.
     *
     * @return string A human readable description of the exception
     */
    public function __toString()
    {
        return "{$this->type} with message \"{$this->message}\"\n" .
               "in {$this->file}:{$this->line}";
    }
}