<?php

namespace pho\Expectation\Matcher;

class TypeMatcher implements MatcherInterface
{
    private $expected;

    private $inverse;

    private $actual;

    public function __construct($expected, $inverse)
    {
        $this->expected = $expected;
        $this->inverse = $inverse;
    }

    public function match($actual)
    {
        $this->actual = gettype($actual);

        $match = ($this->actual === $this->expected);
        $match = ($this->inverse) ? !$match : $match;

        return $match;
    }

    public function getFailureMessage()
    {
        if (!$this->inverse) {
            return "Expected {$this->expected}, got {$this->actual}";
        } else {
            return "Expected a type other than {$this->expected}";
        }
    }
}
