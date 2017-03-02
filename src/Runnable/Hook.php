<?php

namespace pho\Runnable;

use pho\Suite\Suite;

class Hook extends Runnable
{
    /**
     * Constructs a hook object, to be associated with any of a suite's hooks,
     * ie: before, after, beforeEach, and afterEach. The closure is also bound
     * to the suite.
     *
     * @param string   $title   Title of the hook
     * @param \Closure $closure The closure to invoke when the hook is called
     * @param Suite    $suite   The suite within which this spec was defined
     */
    public function __construct($title, \Closure $closure, Suite $suite)
    {
        $this->title = "{$title} hook";
        $this->suite = $suite;
        $this->closure = $closure->bindTo($suite);
    }
}
