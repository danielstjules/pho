<?php

namespace pho\Runnable;

class Hook extends Runnable
{
    public function __construct($context)
    {
        $this->context = $context;
    }
}
