<?php

namespace pho\Expectation\Matcher;

class MaximumMatcher extends AbstractMatcher implements MatcherInterface
{
    private $maximum;

    private $actual;

    /**
     * Creates a new MaximumMatcher for comparing to an expected number.
     *
     * @param int $maximum The maximum expected value
     */
    public function __construct($maximum)
    {
        $this->maximum = $maximum;
    }

    /**
     * Returns true if $actual is less than $maximum, false otherwise.
     *
     * @param  mixed   $actual The value to compare
     * @return boolean Whether or not the value is less than the min
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($this->actual < $this->maximum);
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
            return "Expected {$this->actual} to be less than {$this->maximum}";
        } else {
            return "Expected {$this->actual} not to be less than {$this->maximum}";
        }
    }
}
