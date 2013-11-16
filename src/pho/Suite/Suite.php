<?php

namespace pho\Suite;

class Suite
{
    public $title;

    public $closure;

    public $before;

    public $after;

    public $beforeEach;

    public $afterEach;

    public $parent;

    public $suites;

    public $specs;

    private $store;

    /**
     * Constructs a test suite, which may contain nested suites and specs. The
     * anonymous function passed to the constructor contains the body of the
     * suite to be ran, and it is bound to the suite.
     *
     * @param string   $title   A title to be associated with the suite
     * @param \Closure $closure The closure to invoke when the suite is ran
     */
    public function __construct($title, $closure)
    {
        $this->title = $title;
        $this->closure = $closure->bindTo($this);
        $this->specs = [];
        $this->suites = [];
        $this->store = [];
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

    /**
     * Returns the value for the given key. If not defined within the suite's
     * store, tries to retrieve the value from the parent suite.
     *
     * @return mixed The stored value, or null if none exists
     */
    public function get($key)
    {
        if (isset($this->store[$key])) {
            return $this->store[$key];
        } elseif ($this->parent === null) {
            return null;
        }

        return $this->parent->get($key);
    }

    /**
     * Sets the value stored at the given key for this suite.
     *
     * @param mixed $key They key to update
     * @param mixed $val The value to set at the given key
     */
    public function set($key, $val)
    {
        $this->store[$key] = $val;
    }
}
