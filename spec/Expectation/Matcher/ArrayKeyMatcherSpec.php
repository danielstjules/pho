<?php

use pho\Expectation\Matcher\ArrayKeyMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('ArrayKeyMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new ArrayKeyMatcher('');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the key exists in the array', function() {
            $matcher = new ArraykeyMatcher('test');
            if (!$matcher->match(['test' => 'value'])) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the key does not exist in the array', function() {
            $matcher = new ArraykeyMatcher('value');

            if ($matcher->match(['test' => 'value'])) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the arraykey and actual values', function() {
            $matcher = new ArraykeyMatcher('value');
            $matcher->match(['test' => 'value']);
            $expected = 'Expected array to have the key "value"';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the arraykey and actual values with negated logic', function() {
            $matcher = new ArraykeyMatcher('test');
            $matcher->match(['test' => 'value']);
            $expected = 'Expected array not to have the key "test"';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
