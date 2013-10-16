<?php

namespace pho\Exception;

use pho\Exception\Exception;

class RunnableException extends Exception
{
    public function __construct($exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(),
            $exception);

        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->type = get_class($exception);
    }
}