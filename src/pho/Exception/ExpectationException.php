<?php

namespace pho\Exception;

class ExpectationException extends \Exception
{
    /**
     * Returns a string containing the expectation failure message.
     *
     * @return string A human readable description of the exception
     */
    public function __toString()
    {
        return $this->message;
    }
}