<?php

use pho\Expectation\Matcher\SuffixMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('SuffixMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new SuffixMatcher('test');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the subject contains the suffix', function() {
            $matcher = new SuffixMatcher('123');

            if (!$matcher->match('test123')) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the subject does not contain the suffix', function() {
            $matcher = new SuffixMatcher('12');

            if ($matcher->match('TEST123')) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected suffix', function() {
            $matcher = new SuffixMatcher('test');
            $matcher->match('TEST123');
            $expected = 'Expected "TEST123" to end with "test"';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected suffix with inversed logic', function() {
            $matcher = new SuffixMatcher('test');
            $matcher->match('test123');
            $expected = 'Expected "test123" not to end with "test"';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
