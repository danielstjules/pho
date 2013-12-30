<?php

namespace pho\Expectation\Matcher;

class InclusionMatcher extends AbstractMatcher implements MatcherInterface
{
    private $needles;

    private $matchAll;

    private $haystack;

    private $type;

    private $found;

    private $missing;

    /**
     * Creates a new InclusionMatcher for testing whether or not the values in
     * the $needles array are included in an array or as a substring of a
     * string.
     *
     * @param array   $needles  The strings or elements to check for inclusion
     * @param boolean $matchAll Whether or not to match all needles, or any
     */
    public function __construct($needles, $matchAll = true)
    {
        $this->needles = $needles;
        $this->matchAll = $matchAll;
    }

    /**
     * Checks whether or not the needles are found in the supplied $haystack.
     * Returns true if the needles are found, false otherwise.
     *
     * @param  mixed   $haystack An array or string through which to search
     * @return boolean Whether or not the needles were found
     * @throws \InvalidArgumentException If $haystack isn't an array or string
     */
    public function match($haystack)
    {
        $this->found = [];
        $this->missing = [];

        if (!is_string($haystack) && !is_array($haystack)) {
            throw new \InvalidArgumentException('Argument must be an array or string');
        }

        if (is_string($haystack)) {
            $this->type = 'string';
            foreach ($this->needles as $needle) {
                if (strpos($haystack, $needle) !== false) {
                    $this->found[] = $needle;
                } else {
                    $this->missing[] = $needle;
                }
            }
        } elseif (is_array($haystack)) {
            $this->type = 'array';
            foreach ($this->needles as $needle) {
                if (in_array($needle, $haystack)) {
                    $this->found[] = $needle;
                } else {
                    $this->missing[] = $needle;
                }
            }
        }

        if ($this->matchAll) {
            return (count($this->missing) === 0);
        }

        return (count($this->found) > 0);
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
        $quantifier = (!$this->matchAll) ? 'one of ' : '';

        if (!$inverse) {
            $missing = implode(', ', $this->missing);
            return "Expected {$this->type} to contain {$quantifier}{$missing}";
        } else {
            $found = implode(', ', $this->found);
            return "Expected {$this->type} not to contain {$quantifier}{$found}";
        }
    }
}
