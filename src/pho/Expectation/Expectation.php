<?php

namespace pho\Expectation;

use pho\Exception\ExpectationException;
use pho\Expectation\Matcher\TypeMatcher;
use pho\Expectation\Matcher\StrictEqualityMatcher;
use pho\Expectation\Matcher\LengthMatcher;

class Expectation
{
    private $actual;

    private $inverse;

    /**
     * Creates a new expectation for supplied value.
     *
     * @param mixed $actual The value to test
     */
    public function __construct($actual)
    {
        $this->actual = $actual;
        $this->inverse = false;
    }

    /**
     * Inverts the logic of the expectation, and returns the object.
     *
     * @returns Expectation The object with inverted logic
     */
    public function not()
    {
        $this->inverse = true;

        return $this;
    }

    /**
     * Tests whether or not $actual is, or is not, of a given type.
     *
     * @param  string               $type The name of the type
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeA($type)
    {
        $matcher = new TypeMatcher($type);
        $this->test($matcher);
    }

    /**
     * An alias for Expectation::toBeA()
     *
     * @param  string               $type The name of the type
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeAn($type)
    {
        $this->toBeA($type);
    }

    /**
     * Tests whether or not $actual is equal to a given value.
     *
     * @param  mixed                $value The expected value
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBe($value)
    {
        $matcher = new StrictEqualityMatcher($value);
        $this->test($matcher);
    }

    /**
     * Tests whether or not $actual is null.
     *
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeNull()
    {
        $matcher = new StrictEqualityMatcher(null);
        $this->test($matcher);
    }

    /**
     * Tests whether or not $actual is true.
     *
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeTrue()
    {
        $matcher = new StrictEqualityMatcher(true);
        $this->test($matcher);
    }

    /**
     * Tests whether or not $actual is false.
     *
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeFalse()
    {
        $matcher = new StrictEqualityMatcher(false);
        $this->test($matcher);
    }

    /**
     * Tests whether or not $actual, a string or an array, has a length of 0.
     *
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toBeEmpty()
    {
        $this->toHaveLength(0);
    }

    /**
     * Tests whether or not $actual, a string or an array, has the given length.
     *
     * @param  int                  $length The expected length
     * @throws ExpectationException If the positive or negative match fails
     */
    public function toHaveLength($length)
    {
        $matcher = new LengthMatcher($length);
        $this->test($matcher);
    }

    /**
     * Runs the matcher with $actual, and throws an exception if the xor of the
     * returned value and $inverse is false.
     *
     * @param  MatcherInterface     $matcher
     * @throws ExpectationException If the positive or negative match fails
     */
    private function test($matcher)
    {
        $match = $matcher->match($this->actual);

        if (!($this->inverse xor $match)) {
            $failureMessage = $matcher->getFailureMessage($this->inverse);
            throw new ExpectationException($failureMessage);
        }
    }
}
