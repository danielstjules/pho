<?php

namespace pho\Expectation\Matcher;

class TypeMatcher extends AbstractMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    /**
     * Creates a new TypeMatcher for comparing to an expected type.
     *
     * @param mixed $expected The expected type
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the type of the passed argument to the expected type. Returns
     * true if the two values are of the same type, false otherwise.
     *
     * @param  mixed   $actual The value to test
     * @return boolean Whether or not the value has the expected type
     */
    public function match($actual)
    {
        $this->actual = gettype($actual);

        return ($this->actual === $this->expected);
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
            return "Expected {$this->expected}, got {$this->actual}";
        } else {
            return "Expected a type other than {$this->expected}";
        }
    }
}
