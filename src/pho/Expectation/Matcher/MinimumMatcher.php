<?php

namespace pho\Expectation\Matcher;

class MinimumMatcher extends AbstractMatcher implements MatcherInterface
{
    private $minumum;

    private $actual;

    /**
     * Creates a new MinimumMatcher for comparing to an expected number.
     *
     * @param int $minimum The minimum expected value
     */
    public function __construct($minimum)
    {
        $this->minimum = $minimum;
    }

    /**
     * Returns true if $actual is greater than $minimum, false otherwise.
     *
     * @param  mixed   $actual The value to compare
     * @return boolean Whether or not the value is greater than the min
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($this->actual > $this->minimum);
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
            return "Expected {$this->actual} to be greater than {$this->minimum}";
        } else {
            return "Expected {$this->actual} not to be greater than {$this->minimum}";
        }
    }
}
