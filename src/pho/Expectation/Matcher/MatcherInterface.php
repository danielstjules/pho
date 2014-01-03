<?php

namespace pho\Expectation\Matcher;

interface MatcherInterface
{
    /**
     * The method should return true if the match succeeds.
     *
     * @param  mixed      $actual The actual value to match against
     * @return boolean    Returns true if the desired match succeeds, else false
     * @throws \Exception If $actual isn't of an expected type
     */
    public function match($actual);

    /**
     * The method should return an error message indicating why the match would
     * have failed. If the optional parameter $negated is true, it should
     * return a message corresponding to the negative match.
     *
     * @param  boolean $negated Whether to print a message corresponding
     *                          to the positive match or its negated
     * @return string  The error message
     */
    public function getFailureMessage($negated = false);
}
