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
     * Returns an error message indicating why the match would have failed given
     * the passed value. Returns the inverse of the message if $inverse is true.
     *
     * @param  boolean $inverse Whether or not to print the inverse message
     * @return string  The error message
     */
    public function getFailureMessage($inverse = false)
    {
        if (!$inverse) {
            return "Expected value to be {$this->expected}";
        } else {
            return "Expected value not to be {$this->expected}";
        }
    }
}
