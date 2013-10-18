<?php

namespace pho\Suite;

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

    /**
     * Constructs a test suite, which may contain nested suites and specs. The
     * anonymous function passed to the constructor contains the body of the
     * suite to be ran.
     *
     * @param string   $title   A title to be associated with the suite
     * @param callable $context The closure to invoke when the suite is ran
     */
    public function __construct($title, $context)
    {
        $this->title = $title;
        $this->context = $context;
        $this->specs = [];
        $this->suites = [];
    }

    /**
     * Returns a string containing the parent suite's title, if a child suite,
     * followed by its own title.
     *
     * @return string A human readable description of the suite
     */
    public function __toString()
    {
        if ($this->parent) {
            return "{$this->parent} {$this->title}";
        }

        return $this->title;
    }
}
