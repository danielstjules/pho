<?php

namespace pho;

abstract class Runnable
{
    public $context;

    public function run()
    {
        if (is_callable($this->context)) {
            $this->context->__invoke();
        }
    }
}
