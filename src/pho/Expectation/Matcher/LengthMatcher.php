<?php

namespace pho\Expectation\Matcher;

class LengthMatcher implements MatcherInterface
{
    private $expected;

    private $inverse;

    private $actual;

    private $type;

    public function __construct($expected, $inverse)
    {
        $this->expected = $expected;
        $this->inverse = $inverse;
    }

    public function match($actual)
    {
        if (is_string($actual)) {
            $this->type = 'string';
            $this->actual = strlen($actual);
        } elseif (is_array($actual)) {
            $this->type = 'array';
            $this->actual = count($actual);
        } else {
            throw Exception('LengthMatcher requires an array or string');
        }

        $match = ($this->actual === $this->expected);
        $match = ($this->inverse) ? !$match : $match;

        return $match;
    }

    public function getFailureMessage()
    {
        if (!$this->inverse) {
            return "Expected {$this->type} to have a length of {$this->expected}";
        } else {
            return "Expected {$this->type} not to have a length of {$this->expected}";
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
