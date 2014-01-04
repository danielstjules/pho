<?php

use pho\Expectation\Matcher\TypeMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('TypeMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new TypeMatcher('string');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the value has the expected type', function() {
            $matcher = new TypeMatcher('string');
            if (!$matcher->match('test')) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the value is not of the correct type', function() {
            $matcher = new TypeMatcher('integer');

            if ($matcher->match('test')) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected type and the type of the value', function() {
            $matcher = new TypeMatcher('integer');
            $matcher->match(false);
            $expected = 'Expected integer, got boolean';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected and actual type with negated logic', function() {
            $matcher = new TypeMatcher('integer');
            $matcher->match(0);
            $expected = 'Expected a type other than integer';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
