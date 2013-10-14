<?php

namespace pho;

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

    public function __toString()
    {
        return "{$this->suite} {$this->title}";
    }
}
