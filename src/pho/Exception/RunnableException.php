<?php

namespace pho\Exception;

use pho\Exception\Exception;

class RunnableException extends Exception
{
    /**
     * Creates a RunnableException, given a previously raised exception. This
     * corresponds to an exception thrown during regular execution of the test
     * runner, and is linked to the originating spec.
     *
     * @param \Exception $exception The exception thrown during a spec
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(),
            $exception);

        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->type = get_class($exception);
    }
}