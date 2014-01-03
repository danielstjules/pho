<?php

use pho\Expectation\Matcher\PrefixMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('PrefixMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new PrefixMatcher('test');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the subject contains the prefix', function() {
            $matcher = new PrefixMatcher('test');

            if (!$matcher->match('test123')) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the subject does not contain the prefix', function() {
            $matcher = new PrefixMatcher('test');

            if ($matcher->match('TEST123')) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected prefix', function() {
            $matcher = new PrefixMatcher('test');
            $matcher->match('TEST123');
            $expected = 'Expected "TEST123" to start with "test"';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected prefix with negated logic', function() {
            $matcher = new PrefixMatcher('test');
            $matcher->match('test123');
            $expected = 'Expected "test123" not to start with "test"';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
