<?php

namespace pho\Runnable;

class Spec extends Runnable
{
    public $title;

    public $suite;

    /**
     * Constructs a Spec, to be associated with a particular suite, and ran
     * by the test runner.
     *
     * @param string   $title   A title to be associated with the spec
     * @param callable $context The closure to invoke when the spec is called
     * @param Suite    $suite   The suite within which this spec was defined
     */
    public function __construct($title, $context, $suite)
    {
        $this->title = $title;
        $this->context = $context;
        $this->suite = $suite;
    }

    /**
     * Returns whether or not the spec passed, based on the existence of an
     * exception in the object's $exception property.
     *
     * @return boolean True if the spec passed, false if it failed
     */
    public function passed()
    {
        return (!$this->exception instanceof \Exception);
    }

    /**
     * Returns a string containing the spec's name, preceeded by the names of
     * all parent suites.
     *
     * @return string A human readable description of the spec
     */
    public function __toString()
    {
        return "{$this->suite} {$this->title}";
    }
}
