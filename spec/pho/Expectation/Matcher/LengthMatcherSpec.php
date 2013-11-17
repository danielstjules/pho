<?php

use pho\Expectation\Matcher\LengthMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('LengthMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new LengthMatcher(1);

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the string has the expected length', function() {
            $matcher = new LengthMatcher(3);
            if (!$matcher->match('pho')) {
                throw new \Exception('Does not return true');
            }
        });

        it("returns false if the string doesn't have the given length", function() {
            $matcher = new LengthMatcher(5);
            if ($matcher->match('Test')) {
                throw new \Exception('Does not return false');
            }
        });

        it('returns true if the array has the expected length', function() {
            $matcher = new LengthMatcher(2);
            if (!$matcher->match(['a', 'b'])) {
                throw new \Exception('Does not return true');
            }
        });

        it("returns false if the array doesn't have the expected length", function() {
            $matcher = new LengthMatcher(3);
            if ($matcher->match(['a', 'b'])) {
                throw new \Exception('Does not return false');
            }
        });

        it('throws an exception if not an array or string', function() {
            $exceptionThrown = false;
            try {
                $matcher = new LengthMatcher(2);
                $matcher->match(1);
            } catch (\Exception $exception) {
                $exceptionThrown = true;
            }

            if (!$exceptionThrown) {
                throw new \Exception('Does not throw an exception');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected length', function() {
            $matcher = new LengthMatcher(2);
            $matcher->match('pho');
            $expected = 'Expected string to have a length of 2';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected length with inversed logic', function() {
            $matcher = new LengthMatcher(3);
            $matcher->match(['a', 'b', 'c']);
            $expected = 'Expected array not to have a length of 3';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
