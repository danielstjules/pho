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
     * Returns an error message indicating why the match failed, and the
     * negation of the message if $negated is true.
     *
     * @param  boolean $negated Whether or not to print the negated message
     * @return string  The error message
     */
    public function getFailureMessage($negated = false)
    {
        $x = $this->start;
        $y = $this->end;

        if (!$negated) {
            return "Expected {$this->actual} to be within [$x, $y]";
        } else {
            return "Expected {$this->actual} not to be within [$x, $y]";
        }
    }
}
