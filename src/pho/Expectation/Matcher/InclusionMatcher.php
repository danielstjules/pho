<?php

namespace pho\Expectation\Matcher;

class InclusionMatcher extends AbstractMatcher implements MatcherInterface
{
    private $needle;

    private $haystack;

    private $type;

    /**
     * Creates a new InclusionMatcher for testing whether or not some needle
     * value is included in an array or as a substring of a string.
     *
     * @param int $needle The string or element to check for inclusion
     */
    public function __construct($needle)
    {
        $this->needle = $needle;
    }

    /**
     * Checks whether or not the needle is found in the supplied $haystack.
     * Returns true if the value if the needle is found, false otherwise.
     *
     * @param  mixed   $haystack An array or string through which to search
     * @return boolean Whether or not the needle was found
     * @throws \InvalidArgumentException If $haystack isn't an array or string
     */
    public function match($haystack)
    {
        if (is_string($haystack)) {
            $this->type = 'string';
            return (strpos($haystack, $this->needle) !== false);
        } elseif (is_array($haystack)) {
            $this->type = 'array';
            return (in_array($this->needle, $haystack));
        }

        throw new \InvalidArgumentException('Argument must be an array or string');
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
            return "Expected {$this->type} to contain {$this->needle}";
        } else {
            return "Expected {$this->type} not to contain {$this->needle}";
        }
    }
}
