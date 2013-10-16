<?php

namespace pho\Exception;

abstract class Exception extends \Exception
{
    protected $type;

    public function __toString()
    {
        return "{$this->type} with message '{$this->message}' in " .
               "{$this->file}:{$this->line}";
    }
}