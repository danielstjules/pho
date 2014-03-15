<?php

namespace pho\Runnable;

use pho\Suite\Suite;

class Spec extends Runnable
{
    const PASSED = 'passed';

    const FAILED = 'failed';

    const INCOMPLETE = 'incomplete';

    const PENDING = 'pending';

    private $title;

    private $result;

    private $pending;

    /**
     * Constructs a Spec, to be associated with a particular suite, and ran
     * by the test runner. The closure is bound to the suite.
     *
     * @param string   $title   A title to be associated with the spec
     * @param \Closure $closure The closure to invoke when the spec is called
     * @param Suite    $suite   The suite within which this spec was defined
     */
    public function __construct($title, \Closure $closure = null, Suite $suite)
    {
        $this->title = $title;
        $this->suite = $suite;

        if ($closure) {
            $this->closure = $closure->bindTo($suite);
        }
    }

    /**
     * Mark a Spec as pending.
     */
    public function setPending()
    {
        $this->pending = true;
    }

    /**
     * Invokes Runnable::run(), storing any exception in the corresponding
     * property, followed by setting the specs' result.
     */
    public function run()
    {
        if (true === $this->pending) {
            $this->result = self::PENDING;
        } else {
            parent::run();

            if ($this->closure && !$this->exception) {
                $this->result = self::PASSED;
            } elseif ($this->closure) {
                $this->result = self::FAILED;
            } else {
                $this->result = self::INCOMPLETE;
            }
        }
    }

    /**
     * Returns the title of the spec.
     *
     * @return string The title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the result of the spec, which after running, is one of PASSED,
     * FAILED, or INCOMPLETE.
     */
    public function getResult()
    {
        return $this->result;
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
