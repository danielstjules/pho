<?php

namespace pho\Expectation\Matcher;

class PatternMatcher extends AbstractMatcher implements MatcherInterface
{
    private $pattern;

    private $actual;

    /**
     * Creates a new PatternMatcher for matching a string against a regular
     * expression.
     *
     * @param mixed $pattern The pattern to match
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Tries to match the given string with the expected pattern. Returns
     * true if it matches, false otherwise.
     *
     * @param  mixed   $actual The string to test
     * @return boolean Whether or not the string matches the expect pattern
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return (preg_match($this->pattern, $actual) > 0);
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
            return "Expected {$this->actual} to match {$this->pattern}";
        } else {
            return "Expected {$this->actual} not to match {$this->pattern}";
        }
    }
}
