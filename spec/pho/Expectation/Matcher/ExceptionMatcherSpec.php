<?php

use pho\Expectation\Matcher\ExceptionMatcher;
use pho\Expectation\Matcher\MatcherInterface;

describe('ExceptionMatcher', function() {
    it('implements the MatcherInterface', function() {
        $matcher = new ExceptionMatcher('');

        if (!($matcher instanceof MatcherInterface)) {
            throw new \Exception('Does not implement MatcherInterface');
        }
    });

    context('match', function() {
        it('returns true if the callable threw the expected exception', function() {
            $callable = function() {
                throw new pho\Exception\ErrorException(E_ERROR, 'test');
            };

            $matcher = new ExceptionMatcher('pho\Exception\ErrorException');
            if (!$matcher->match($callable)) {
                throw new \Exception('Does not return true');
            }
        });

        it('returns false if the callable threw a different exception', function() {
            $callable = function() {
                throw new pho\Exception\ErrorException(E_ERROR, 'test');
            };

            $matcher = new ExceptionMatcher('ErrorException');
            if ($matcher->match($callable)) {
                throw new \Exception('Does not return false');
            }
        });

        it('returns false if the callable did not throw an exception', function() {
            $callable = function() {
                return;
            };

            $matcher = new ExceptionMatcher('RunnablException');
            if ($matcher->match($callable)) {
                throw new \Exception('Does not return false');
            }
        });
    });

    context('getFailureMessage', function() {
        it('lists the expected and thrown exception', function() {
            $callable = function() {
                throw new pho\Exception\ErrorException(E_ERROR, 'test');
            };

            $matcher = new ExceptionMatcher('pho\Exception\RunnableException');
            $matcher->match($callable);

            $expected = 'Expected pho\Exception\RunnableException to be ' .
                        'thrown, got pho\Exception\ErrorException';
            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists the expected exception if none thrown', function() {
            $callable = function() {
                return;
            };

            $matcher = new ExceptionMatcher('pho\Exception\RunnableException');
            $matcher->match($callable);

            $expected = 'Expected pho\Exception\RunnableException to be ' .
                        'thrown, none thrown';
            if ($expected !== $matcher->getFailureMessage()) {
                throw new \Exception('Did not return expected failure message');
            }
        });

        it('lists expected and resulting output with inversed logic', function() {
            $callable = function() {
                throw new pho\Exception\ErrorException(E_ERROR, 'test');
            };

            $matcher = new ExceptionMatcher('pho\Exception\ErrorException');
            $matcher->match($callable);

            $expected = 'Expected pho\Exception\ErrorException not to be thrown';
            if ($expected !== $matcher->getFailureMessage(true)) {
                throw new \Exception('Did not return expected failure message');
            }
        });
    });
});
