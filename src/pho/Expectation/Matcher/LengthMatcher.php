<?php

namespace pho\Expectation\Matcher;

class LengthMatcher extends AbstractMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    private $type;

    /**
     * Creates a new LengthMatcher for testing the length of a string or array.
     *
     * @param int $expected The expected length
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the length of the given array or string to the expected value.
     * Returns true if the value is of the expected length, else false.
     *
     * @param  mixed      $actual An array or string for which to test the length
     * @return boolean    Whether or not the value is of the expected length
     * @throws \Exception If $actual isn't of type array or string
     */
    public function match($actual)
    {
        if (is_string($actual)) {
            $this->type = 'string';
            $this->actual = strlen($actual);
        } elseif (is_array($actual)) {
            $this->type = 'array';
            $this->actual = count($actual);
        } else {
            throw \Exception('LengthMatcher::match() requires an array or string');
        }

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
        if (!$inverse) {
            return "Expected {$this->type} to have a length of {$this->expected}";
        } else {
            return "Expected {$this->type} not to have a length of {$this->expected}";
        }
    }
}
