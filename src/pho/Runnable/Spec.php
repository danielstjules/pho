<?php

namespace pho\Runnable;

class Spec extends Runnable
{
    public $title;

    public $suite;

    public function __construct($title, $context, $suite)
    {
        $this->title = $title;
        $this->context = $context;
        $this->suite = $suite;
    }

    public function passed()
    {
        return (!$this->exception instanceof \Exception);
    }

    public function __toString()
    {
        return "{$this->suite} {$this->title}";
    }
}
