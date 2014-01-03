<?php

use pho\Expectation\Matcher\MaximumMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('MaximumMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new MaximumMatcher(1);

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the value is less than the max', function() {
            $matcher = new MaximumMatcher(2);
            if (!$matcher->match(0)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the value is greater than the max', function() {
            $matcher = new MaximumMatcher(-1);

            if ($matcher->match(10.1)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the maximum and actual values', function() {
            $matcher = new MaximumMatcher(1);
            $matcher->match(1);
            $expected = 'Expected 1 to be less than 1';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the maximum and actual values with negated logic', function() {
            $matcher = new MaximumMatcher(2.2);
            $matcher->match(2.1999);
            $expected = 'Expected 2.1999 not to be less than 2.2';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
