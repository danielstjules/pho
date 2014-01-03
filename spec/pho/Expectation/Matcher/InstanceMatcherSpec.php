<?php

use pho\Expectation\Matcher\InstanceMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('InstanceMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new InstanceMatcher('stdClass');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the object is instance of the class', function() {
            $matcher = new InstanceMatcher('stdClass');
            if (!$matcher->match(new stdClass())) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the object is not an instance', function() {
            $matcher = new InstanceMatcher('Closure');

            if ($matcher->match(new stdClass())) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected class and the class of the object', function() {
            $matcher = new InstanceMatcher('Closure');
            $matcher->match(new stdClass());
            $expected = 'Expected an instance of Closure, got stdClass';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected type and needle with negated logic', function() {
            $matcher = new InstanceMatcher('stdClass');
            $matcher->match(new stdClass());
            $expected = 'Expected an instance other than stdClass';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
