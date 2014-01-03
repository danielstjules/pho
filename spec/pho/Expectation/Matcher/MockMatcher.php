<?php

namespace spec\Mock;

use pho\Expectation\Expectation;
use pho\Expectation\Matcher\MatcherInterface;

class MockMatcher implements MatcherInterface
{
    protected $expected;

    /**
     * Creates a new MockMatcher.
     *
     * @param mixed $expected The expected value
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Returns true if passed the expected value, false otherwise.
     *
     * @param  mixed   $value The actual value to test
     * @return boolean Whether or not the value is strictly equal
     */
    public function match($value)
    {
        return ($value === $this->expected);
    }

    /**
     * Returns an error message indicating why the match failed, and the
     * negation of the message if $negated is true.
     *
     * @param  boolean $negated Whether or not to print the negated message
     * @return string  The error message
     */
    public function getFailureMessage($negated = false)
    {
        if (!$negated) {
            return "Expected value to be {$this->expected}";
        } else {
            return "Expected value not to be {$this->expected}";
        }
    }
}
