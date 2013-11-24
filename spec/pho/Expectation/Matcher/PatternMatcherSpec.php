<?php

use pho\Expectation\Matcher\PatternMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('PatternMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new PatternMatcher('/test/');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the subject matches the pattern', function() {
            $matcher = new PatternMatcher('/test\w{3}/i');

            if (!$matcher->match('TESTing')) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false subject does not match the pattern', function() {
            $matcher = new PatternMatcher('/test\w{3}/');

            if ($matcher->match('TESTing')) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected type and the type of the value', function() {
            $matcher = new PatternMatcher('/\w*/');
            $matcher->match('123');
            $expected = 'Expected 123 to match /\w*/';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected and actual type with inversed logic', function() {
            $matcher = new PatternMatcher('/\w*/');
            $matcher->match('abc');
            $expected = 'Expected abc not to match /\w*/';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
