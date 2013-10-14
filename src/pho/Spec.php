<?php

namespace pho;

class Spec extends Runnable
{
    public $title;

    public function __construct($title, $context)
    {
        $this->title = $title;
        $this->context = $context;
        $this->errors = [];
        $this->exceptions = [];
    }
}
