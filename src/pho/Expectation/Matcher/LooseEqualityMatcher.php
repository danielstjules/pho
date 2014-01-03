<?php

namespace pho\Expectation\Matcher;

class LooseEqualityMatcher extends AbstractMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    /**
     * Creates a new LooseEqualityMatcher for comparing to an expected value.
     *
     * @param mixed $expected The expected value
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the passed argument to the expected value. Returns true if the
     * two values are loosely equal using the == operator, false otherwise.
     *
     * @param  mixed   $actual The value to test
     * @return boolean Whether or not the value is equal
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($this->actual == $this->expected);
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
        $actual = $this->getStringValue($this->actual);
        $expected = $this->getStringValue($this->expected);

        if (!$negated) {
            return "Expected $actual to equal $expected";
        } else {
            return "Expected $actual not to equal $expected";
        }
    }
}
