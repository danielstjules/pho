<?php

namespace pho\Expectation\Matcher;

class ArrayKeyMatcher extends AbstractMatcher implements MatcherInterface
{
    private $key;

    private $array;

    /**
     * Creates a new ArrayKeyMatcher for testing the existence of a key.
     *
     * @param int $key The expected key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns true if the key exists in the given array, false otherwise.
     *
     * @param  array      $array The array in which to check for the key
     * @return boolean    Whether or not the key exists in the array
     * @throws \InvalidArgumentException If $array isn't an array
     */
    public function match($array)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException('Argument must be an array');
        }

        return (array_key_exists($this->key, $array));
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
            return "Expected array to have the key \"{$this->key}\"";
        } else {
            return "Expected array not to have the key \"{$this->key}\"";
        }
    }
}
