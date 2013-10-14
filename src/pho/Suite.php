<?php

namespace pho;

class Suite
{
    public $title;

    public $context;

    public $before;

    public $after;

    public $beforeEach;

    public $afterEach;

    public $parent;

    public $suites;

    public $specs;

    public function __construct($title, $context)
    {
        $this->title = $title;
        $this->context = $context;
        $this->specs = [];
        $this->suites = [];
    }
}
