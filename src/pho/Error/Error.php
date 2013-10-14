<?php

namespace pho\Error;

abstract class Error
{
    public $type;

    public $message;

    public $file;

    public $line;

    public function __construct($type, $message, $file = null, $line = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    public function __toString()
    {
        return "{$this->type} with message '{$this->message}' in " .
               "{$this->file}:{$this->line}";
    }
}