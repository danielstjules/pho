<?php

use pho\Expectation\Matcher\LooseEqualityMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('LooseEqualityMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new LooseEqualityMatcher('stdClass');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if loosely equal', function() {
            $matcher = new LooseEqualityMatcher(true);
            if (!$matcher->match(1)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if not loosely equal', function() {
            $matcher = new LooseEqualityMatcher(false);

            if ($matcher->match(['a', 'b'])) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected equality', function() {
            $matcher = new LooseEqualityMatcher(true);
            $matcher->match(null);
            $expected = 'Expected null to equal true';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected equality with inversed logic', function() {
            $matcher = new LooseEqualityMatcher('test');
            $matcher->match('test');
            $expected = 'Expected "test" not to equal "test"';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
