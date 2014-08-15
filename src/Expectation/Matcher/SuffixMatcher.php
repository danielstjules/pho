<?php

namespace pho\Expectation\Matcher;

class SuffixMatcher extends AbstractMatcher implements MatcherInterface
{
    private $suffix;

    private $subject;

    /**
     * Creates a new suffixMatcher for testing the suffix of a string.
     *
     * @param mixed $suffix The suffix to check for
     */
    public function __construct($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Returns true if the subject ends with the given suffix, false otherwise.
     *
     * @param  mixed   $subject The string to test
     * @return boolean Whether or not the string contains the suffix
     */
    public function match($subject)
    {
        $this->subject = $subject;

        $suffixLength = strlen($this->suffix);
        if (!$suffixLength) {
            return true;
        }

        return (substr($subject, -$suffixLength) === $this->suffix);
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
            return "Expected \"{$this->subject}\" to end with \"{$this->suffix}\"";
        } else {
            return "Expected \"{$this->subject}\" not to end with \"{$this->suffix}\"";
        }
    }
}
