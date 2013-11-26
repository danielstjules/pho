<?php

namespace pho\Expectation;

use pho\Exception\ExpectationException;

use pho\Expectation\Matcher\InstanceMatcher;
use pho\Expectation\Matcher\TypeMatcher;
use pho\Expectation\Matcher\LooseEqualityMatcher;
use pho\Expectation\Matcher\StrictEqualityMatcher;

use pho\Expectation\Matcher\LengthMatcher;
use pho\Expectation\Matcher\InclusionMatcher;
use pho\Expectation\Matcher\ArrayKeyMatcher;

use pho\Expectation\Matcher\PrintMatcher;

use pho\Expectation\Matcher\PatternMatcher;
use pho\Expectation\Matcher\PrefixMatcher;
use pho\Expectation\Matcher\SuffixMatcher;

use pho\Expectation\Matcher\MinimumMatcher;
use pho\Expectation\Matcher\MaximumMatcher;
use pho\Expectation\Matcher\RangeMatcher;

class Expectation
{
    private $actual;

    private $inverse;

    /**
     * Creates a new expectation for supplied value.
     *
     * @param mixed $actual The value to test
     */
    public function __construct($actual = null)
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
     * @param   string               $type The name of the type
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeA($type)
    {
        $matcher = new TypeMatcher($type);
        $this->test($matcher);

        return $this;
    }

    /**
     * An alias for Expectation::toBeA()
     *
     * @param   string               $type The name of the type
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeAn($type)
    {
        return $this->toBeA($type);
    }

    /**
     * Tests whether or not $actual is an instance of the given class.
     *
     * @param   string               $class The name of the class
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeAnInstanceOf($class)
    {
        $matcher = new InstanceMatcher($class);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is equal to a given value.
     *
     * @param   mixed                $value The expected value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBe($value)
    {
        $matcher = new StrictEqualityMatcher($value);
        $this->test($matcher);

        return $this;
    }

    /**
     * An alias for Expectation::toBe
     *
     * @param   mixed                $value The expected value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toEqual($value)
    {
        $matcher = new StrictEqualityMatcher($value);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is null.
     *
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeNull()
    {
        $matcher = new StrictEqualityMatcher(null);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is true.
     *
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeTrue()
    {
        $matcher = new StrictEqualityMatcher(true);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is false.
     *
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeFalse()
    {
        $matcher = new StrictEqualityMatcher(false);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is loosely equal to a given value.
     *
     * @param   mixed                $value The expected value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toEql($value)
    {
        $matcher = new LooseEqualityMatcher($value);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual, a string or an array, has a length of 0.
     *
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeEmpty()
    {
        return $this->toHaveLength(0);
    }

    /**
     * Tests whether or not $actual, a string or an array, has the given length.
     *
     * @param   int                  $length The expected length
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toHaveLength($length)
    {
        $matcher = new LengthMatcher($length);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual, a string or an array, contains a substring
     * or an element with the supplied $value.
     *
     * @param   mixed                $value The value expected to be included
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toContain($value)
    {
        $matcher = new InclusionMatcher($value);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual, a callable function, tried to output the
     * string $value to php://output
     *
     * @param   mixed                $value The expected value to be printed
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toPrint($value)
    {
        $matcher = new PrintMatcher($value);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual matches the given regex pattern.
     *
     * @param   string               $pattern The expected pattern
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toMatch($pattern)
    {
        $matcher = new PatternMatcher($pattern);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual starts with the given substring.
     *
     * @param   string               $substring The expected start of the string
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toStartWith($substring)
    {
        $matcher = new PrefixMatcher($substring);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual ends with the given substring.
     *
     * @param   string               $substring The expected end of the string
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toEndWith($substring)
    {
        $matcher = new SuffixMatcher($substring);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not $actual is greater than a minimum value.
     *
     * @param   int                  $min The minimum value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeGreaterThan($min)
    {
        $matcher = new MinimumMatcher($min);
        $this->test($matcher);

        return $this;
    }

    /**
     * An alias for Expectation::toBeGreaterThan()
     *
     * @param   int                  $min The minimum value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeAbove($min)
    {
        return $this->toBeGreaterThan($min);
    }

    /**
     * Tests whether or not $actual is less than a maximum value.
     *
     * @param   int                  $max The maximum value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeLessThan($max)
    {
        $matcher = new MaximumMatcher($max);
        $this->test($matcher);

        return $this;
    }

    /**
     * An alias for Expectation::toBeLessThan()
     *
     * @param   int                  $max The maximum value
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeBelow($max)
    {
        return $this->toBeLessThan($max);
    }

    /**
     * Tests whether or not $actual is within an inclusive range.
     *
     * @param   int                  $start The left bound of the range
     * @param   int                  $end   The right bound of the range
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toBeWithin($start, $end)
    {
        $matcher = new RangeMatcher($start, $end);
        $this->test($matcher);

        return $this;
    }

    /**
     * Tests whether or not the $key is in the array.
     *
     * @param   mixed                $key The key to check for
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    public function toHaveKey($key)
    {
        $matcher = new ArrayKeyMatcher($key);
        $this->test($matcher);

        return $this;
    }

    /**
     * Runs the matcher with $actual, and throws an exception if the xor of the
     * returned value and $inverse is false.
     *
     * @param   MatcherInterface     $matcher
     * @returns Expectation          The current expectation
     * @throws  ExpectationException If the positive or negative match fails
     */
    private function test($matcher)
    {
        $match = $matcher->match($this->actual);

        if (!($this->inverse xor $match)) {
            $failureMessage = $matcher->getFailureMessage($this->inverse);
            throw new ExpectationException($failureMessage);
        }
    }

    /**
     * Attempts to resolve calls to the 'not' versions of the Expectation
     * methods. This is done by removing the 'not' and converting the
     * first character to lowercase.
     *
     * @param string $method The method to call
     * @param mixed  $arg    An optional argument to pass to the method
     */
    public function __call($method, $argument = null)
    {
        // Check if the method starts with 'not'
        if (strpos($method, 'not') === 0 && strlen($method) > 3) {
            $methodName = lcfirst(substr($method, 3));
        }

        // If method exists, call not() followed by the method
        if (isset($methodName) && method_exists($this, $methodName)) {
            $this->not();
            call_user_func_array([$this, $methodName], $argument);
        } else {
            $exceptionMessage = "Call to undefined method: Expectation::$method";
            throw new \BadMethodCallException($exceptionMessage);
        }
    }
}
