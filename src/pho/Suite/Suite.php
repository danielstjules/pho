<?php

namespace pho\Suite;

use pho\Runnable\Hook;

class Suite
{
    private $title;

    private $closure;

    private $parent;

    private $hooks;

    private $suites;

    private $specs;

    private $store;

    /**
     * Constructs a test suite, which may contain nested suites and specs. The
     * anonymous function passed to the constructor contains the body of the
     * suite to be ran, and it is bound to the suite.
     *
     * @param string   $title   A title to be associated with the suite
     * @param \Closure $closure The closure to invoke when the suite is ran
     * @param Suite    $parent  An optional parent suite
     */
    public function __construct($title, \Closure $closure, Suite $parent = null)
    {
        $this->title = $title;
        $this->closure = $closure->bindTo($this);
        $this->specs = [];
        $this->suites = [];
        $this->store = [];
        $this->parent = $parent;
    }

    /**
     * Returns the Suite's title.
     *
     * @return string The title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the Suite's closure, which may contain definitions of additional
     * specs and suites.
     *
     * @return string The title
     */
    public function getClosure()
    {
        return $this->closure;
    }

    /**
     * Returns the parent suite, if set.
     *
     * @return string The title
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns the hook found at the specified key. Usually one of before,
     * after, beforeEach, or afterEach.
     *
     * @param  string $key The key for the hook
     * @return Hook   The given hook
     */
    public function getHook($key)
    {
        if (isset($this->hooks[$key])) {
            return $this->hooks[$key];
        }

        return null;
    }

    /**
     * Sets a hook at the specified key.
     *
     * @param  string $key The key for the hook
     * @return Hook   The given hook
     */
    public function setHook($key, Hook $hook)
    {
       $this->hooks[$key] = $hook;
    }

    /**
     * Returns an array of suites, which consists of nested suites.
     *
     * @return array The array of suites
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * Adds a suite to the list of nested suites.
     *
     * @param Suite $suite The suite to add
     */
    public function addSuite($suite)
    {
        $this->suites[] = $suite;
    }

    /**
     * Returns an array of specs contained within the suite.
     *
     * @return array The array of specs
     */
    public function getSpecs()
    {
        return $this->specs;
    }

    /**
     * Adds a spec to the list of specs.
     *
     * @param Suite $suite The spec to add
     */
    public function addSpec($spec)
    {
        $this->specs[] = $spec;
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
    public function __get($key)
    {
        if (isset($this->store[$key])) {
            return $this->store[$key];
        } elseif ($this->parent === null) {
            return null;
        }

        return $this->parent->$key;
    }

    /**
     * Sets the value stored at the given key for this suite.
     *
     * @param mixed $key They key to update
     * @param mixed $val The value to set at the given key
     */
    public function __set($key, $val)
    {
        $this->store[$key] = $val;
    }
}
