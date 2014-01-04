<?php

use pho\Expectation\Matcher\MinimumMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('MinimumMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new MinimumMatcher(1);

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the value is greater than the min', function() {
            $matcher = new MinimumMatcher(1);
            if (!$matcher->match(2)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the value is less than the min', function() {
            $matcher = new MinimumMatcher(2);

            if ($matcher->match(-1)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the minimum and the actual values', function() {
            $matcher = new MinimumMatcher(1);
            $matcher->match(1);
            $expected = 'Expected 1 to be greater than 1';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the minimum and actual values with negated logic', function() {
            $matcher = new MinimumMatcher(2);
            $matcher->match(3.1);
            $expected = 'Expected 3.1 not to be greater than 2';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
