<?php

use pho\Expectation\Matcher\RangeMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('RangeMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new RangeMatcher(1, 2);

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the value is within the range', function() {
            $matcher = new RangeMatcher(1, 2);
            if (!$matcher->match(1)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the value is not within the range', function() {
            $matcher = new RangeMatcher(1, 2);

            if ($matcher->match(2.1)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the range and actual values', function() {
            $matcher = new RangeMatcher(1, 2);
            $matcher->match(0);
            $expected = 'Expected 0 to be within [1, 2]';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the range and actual values with inversed logic', function() {
            $matcher = new RangeMatcher(1.1, 10);
            $matcher->match(1.1);
            $expected = 'Expected 1.1 not to be within [1.1, 10]';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
