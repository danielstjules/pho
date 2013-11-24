<?php

namespace pho\Expectation\Matcher;

class InstanceMatcher extends AbstractMatcher implements MatcherInterface
{
    private $expected;

    private $actual;

    /**
     * Creates a new InstanceMatcher for comparing to an expected class.
     *
     * @param string $expected The expected class name
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Compares the class of the passed argument to the expected class name.
     * Returns true if $actual is an instance of the expected class, else false.
     *
     * @param  mixed   $actual The object to compare
     * @return boolean Whether or not an instance of the expected class
     */
    public function match($actual)
    {
        $this->actual = $actual;

        return ($this->actual instanceof $this->expected);
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
        $actualClass = get_class($this->actual);

        if (!$inverse) {
            return "Expected an instance of {$this->expected}, got {$actualClass}";
        } else {
            return "Expected an instance other than {$this->expected}";
        }
    }
}
