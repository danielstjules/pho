<?php

namespace pho\Expectation\Matcher;

class PrintMatcher extends AbstractMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    /**
     * Creates a new PrintMatcher for comparing to an expected output.
     *
     * @param string $expected The expected output
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the output printed by the callable to the expected output.
     * Returns true if the two strings are equal, false otherwise.
     *
     * @param  callable $callable The function to invoke
     * @return boolean  Whether or not the printed and expected output are equal
     */
    public function match($callable)
    {
        ob_start();
        $callable();

        $this->actual = ob_get_contents();
        ob_end_clean();

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
        if (!$negated) {
            return "Expected \"{$this->expected}\", got \"{$this->actual}\"";
        } else {
            return "Expected output other than \"{$this->expected}\"";
        }
    }
}
