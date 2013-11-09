<?php

namespace pho\Expectation\Matcher;

class StrictEqualityMatcher implements MatcherInterface
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
        $this->actual = $actual;

        $match = ($this->actual === $this->expected);
        $match = ($this->inverse) ? !$match : $match;

        return $match;
    }

    public function getFailureMessage()
    {
        $actual = $this->getStringValue($this->actual);
        $expected = $this->getStringValue($this->expected);

        if (!$this->inverse) {
            return "Expected $actual to be $expected";
        } else {
            return "Expected $actual not to be $expected";
        }
    }

    private function getStringValue($value) {
        if ($value === true) {
            return 'true';
        } else if ($value === false) {
            return 'false';
        } else if($value === null) {
            return 'null';
        }

        return print_r($value, true);
    }
}
