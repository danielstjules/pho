<?php

namespace pho\Expectation\Matcher;

class StrictEqualityMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    /**
     * Creates a new StrictEqualityMatcher for comparing to an expected value.
     *
     * @param mixed $expected The expected value
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the passed argument to the expected value. Returns true if the
     * two values are strictly equal, false otherwise.
     *
     * @param  mixed   $actual The value to test
     * @return boolean Whether or not the value is equal
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($this->actual === $this->expected);
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
        $actual = $this->getStringValue($this->actual);
        $expected = $this->getStringValue($this->expected);

        if (!$inverse) {
            return "Expected $actual to be $expected";
        } else {
            return "Expected $actual not to be $expected";
        }
    }

    /**
     * Returns a string representation of the given value, used for printing
     * the failure message.
     *
     * @param   mixed  $value The value for which to get a string representation
     * @returns string The string in question
     */
    private function getStringValue($value)
    {
        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif($value === null) {
            return 'null';
        }

        return print_r($value, true);
    }
}
