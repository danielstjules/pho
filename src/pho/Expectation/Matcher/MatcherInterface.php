<?php

namespace pho\Expectation\Matcher;

interface MatcherInterface
{
    /**
     * The constructor must accept an expected value as well a boolean matchType
     * to indicate whether or not we want a positive or negative match.
     *
     * @param mixed   $expected  The expected value
     * @param boolean $matchType True to indicate a positive match, false for a
     *                           negative match
     */
    public function __construct($expected, $matchType);

    /**
     * The method should return true if the positive or negative match succeeds,
     * and false otherwise.
     *
     * @param  mixed   $actual The actual value to match against
     * @return boolean Returns true if the desired match type succeeds, else false
     */
    public function match($actual);

    /**
     * The method should return an error message indicating why the match failed.
     *
     * @return string The error message
     */
    public function getFailureMessage();
}
