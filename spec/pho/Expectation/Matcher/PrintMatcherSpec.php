<?php

use pho\Expectation\Matcher\PrintMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('PrintMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new PrintMatcher('');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the callable printed the expected output', function() {
            $callable = function() {
                echo 'test';
            };

            $matcher = new PrintMatcher('test');
            if (!$matcher->match($callable)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the callable did not print the output', function() {
            $callable = function() {
                echo 'testing' . PHP_EOL;
            };

            $matcher = new PrintMatcher('testing');
            if ($matcher->match($callable)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected and resulting output', function() {
            $matcher = new PrintMatcher('testing');
            $matcher->match(function() {
                echo 'test';
            });

            $expected = 'Expected "testing", got "test"';
            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists expected and resulting output with inversed logic', function() {
            $matcher = new PrintMatcher('testing');
            $matcher->match(function() {
                echo 'testing';
            });

            $expected = 'Expected output other than "testing"';
            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
