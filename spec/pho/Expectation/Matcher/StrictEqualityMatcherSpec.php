<?php

use pho\Expectation\Matcher\StrictEqualityMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('StrictEqualityMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new StrictEqualityMatcher('stdClass');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if strictly equal', function() {
            $matcher = new StrictEqualityMatcher(false);
            if (!$matcher->match(false)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if not strictly equal', function() {
            $matcher = new StrictEqualityMatcher(null);

            if ($matcher->match(false)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected equality', function() {
            $matcher = new StrictEqualityMatcher(0);
            $matcher->match(false);
            $expected = 'Expected false to be 0';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected equality with inversed logic', function() {
            $matcher = new StrictEqualityMatcher(null);
            $matcher->match(null);
            $expected = 'Expected null not to be null';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
