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

    public function __construct($actual)
    {
        $this->actual = $actual;
        $this->inverse = false;
    }

    public function not()
    {
        $this->inverse = true;

        return $this;
    }

    public function toBeA($type)
    {
        $matcher = new TypeMatcher($type, $this->inverse);
        $this->test($matcher);
    }

    public function toBeAn($type)
    {
        return $this->toBeA($type);
    }

    public function toBe($value)
    {
        $matcher = new StrictEqualityMatcher($value, $this->inverse);
        $this->test($matcher);
    }

    public function toBeNull()
    {
        $matcher = new StrictEqualityMatcher(null, $this->inverse);
        $this->test($matcher);
    }

    public function toBeTrue()
    {
        $matcher = new StrictEqualityMatcher(true, $this->inverse);
        $this->test($matcher);
    }

    public function toBeFalse()
    {
        $matcher = new StrictEqualityMatcher(false, $this->inverse);
        $this->test($matcher);
    }

    public function toBeEmpty()
    {
        $this->toHaveLength(0);
    }

    public function toHaveLength($length)
    {
        $matcher = new LengthMatcher($length, $this->inverse);
        $this->test($matcher);
    }

    private function test($matcher)
    {
        if (!$matcher->match($this->actual)) {
            throw new ExpectationException($matcher->getFailureMessage());
        }
    }
}
