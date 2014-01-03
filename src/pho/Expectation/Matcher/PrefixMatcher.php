<?php

namespace pho\Expectation\Matcher;

class PrefixMatcher extends AbstractMatcher implements MatcherInterface
{
    private $prefix;

    private $subject;

    /**
     * Creates a new PrefixMatcher for testing the prefix of a string.
     *
     * @param mixed $prefix The prefix to check for
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Returns true if the subject starts with the given prefix, false otherwise.
     *
     * @param  mixed   $subject The string to test
     * @return boolean Whether or not the string contains the prefix
     */
    public function match($subject)
    {
        $this->subject = $subject;

        return (strpos($subject, $this->prefix) === 0);
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
            return "Expected \"{$this->subject}\" to start with \"{$this->prefix}\"";
        } else {
            return "Expected \"{$this->subject}\" not to start with \"{$this->prefix}\"";
        }
    }
}
