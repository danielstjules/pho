<?php

use pho\Expectation\Matcher\InclusionMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('InclusionMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new InclusionMatcher('test');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the substring is found in the string', function() {
            $matcher = new InclusionMatcher('String');
            if (!$matcher->match('TestString')) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the substring is not found in the string', function() {
            $matcher = new InclusionMatcher('String');
            if ($matcher->match('Test')) {
                throw new \Exception('Does not return false');
            }
        });

        it('returns true if the value is in the array', function() {
            $matcher = new InclusionMatcher(2);
            if (!$matcher->match([1, 2, 3])) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the value is not in the array', function() {
            $matcher = new InclusionMatcher(4);
            if ($matcher->match([1, 2, 3])) {
                throw new \Exception('Does not return false');
            }
        });

        it("throws an exception if haystack isn't an array or string", function() {
            $exceptionThrown = false;
            try {
                $matcher = new InclusionMatcher('test');
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
        it('lists the expected type and needle', function() {
            $matcher = new InclusionMatcher('Testing');
            $matcher->match('TestString');
            $expected = 'Expected string to contain Testing';

            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected type and needle with inversed logic', function() {
            $matcher = new InclusionMatcher('Test');
            $matcher->match('TestString');
            $expected = 'Expected string not to contain Test';

            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
