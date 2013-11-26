<?php

namespace pho\Expectation\Matcher;

class RangeMatcher extends AbstractMatcher implements MatcherInterface
{
    private $start;

    private $end;

    /**
     * Creates a new RangeMatcher for testing whether or not a value is within
     * an inclusive range.
     *
     * @param int $start The left bound of the range
     * @param int $end   The right bound of the range
     */
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Returns true if $actual is within the inclusive range, false otherwise.
     *
     * @param  mixed   $actual The value to compare
     * @return boolean Whether or not the value is within the range
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($actual >= $this->start && $actual <= $this->end);
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
        $x = $this->start;
        $y = $this->end;

        if (!$inverse) {
            return "Expected {$this->actual} to be within [$x, $y]";
        } else {
            return "Expected {$this->actual} not to be within [$x, $y]";
        }
    }
}
