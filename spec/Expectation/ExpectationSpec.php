<?php

namespace pho\spec\Expectation;

use pho\Expectation\Expectation;
use pho\Exception\ExpectationException;

// Load MockMatcher for testing custom matchers
include_once(dirname(__FILE__) . '/Matcher/MockMatcher.php');

// Define a helper for testing the expectations
function shouldThrowException($closure, $class = null) {
    $class = ($class) ?: 'pho\Exception\ExpectationException';
    $exceptionThrown = false;

    try {
        $closure();
    } catch (\Exception $exception) {
        $exceptionThrown = true;
    }

    if (!$exceptionThrown) {
        throw new \Exception('Does not throw an exception');
    }

    if (!($exception instanceof $class)) {
        throw new \Exception("Does not throw a $class");
    }
}

describe('Expectation', function() {
    context('toBeA', function() {
        it('returns if the value has the expected type', function() {
            $expect = new Expectation('test');
            $expect->toBeA('string');
        });

        it('throws exception if not of the expected type', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->toBeA('string');
            });
        });
    });

    context('notToBeA', function() {
        it('returns if the value is not the given type', function() {
            $expect = new Expectation(1);
            $expect->notToBeA('string');
        });

        it('throws exception if it is of the given type', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test');
                $expect->notToBeA('string');
            });
        });
    });

    context('toBeAn', function() {
        it('returns if the value has the expected type', function() {
            $expect = new Expectation(123);
            $expect->toBeAn('integer');
        });

        it('throws exception if not of the expected type', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test');
                $expect->toBeAn('integer');
            });
        });
    });

    context('notToBeAn', function() {
        it('returns if the value is not the given type', function() {
            $expect = new Expectation('test');
            $expect->notToBeAn('integer');
        });

        it('throws exception if it is of the given type', function() {
            shouldThrowException(function() {
                $expect = new Expectation(123);
                $expect->notToBeAn('integer');
            });
        });
    });

    context('toBeAnInstanceOf', function() {
        it('returns if the object has the expected class', function() {
            $expect = new Expectation(new \stdClass());
            $expect->toBeAnInstanceOf('stdClass');
        });

        it('throws exception if not of the expected class', function() {
            shouldThrowException(function() {
                $closure = function() {};
                $expect = new Expectation($closure);
                $expect->toBeAnInstanceOf('stdClass');
            });
        });
    });

    context('notToBeAnInstanceOf', function() {
        it('returns if the object if not of the expected class', function() {
            $expect = new Expectation(new \stdClass());
            $expect->notToBeAnInstanceOf('Closure');
        });

        it('throws exception if it has the expected class', function() {
            shouldThrowException(function() {
                $expect = new Expectation(new \stdClass());
                $expect->notToBeAnInstanceOf('stdClass');
            });
        });
    });

    context('toBe', function() {
        it('returns if the value is strictly equal', function() {
            $expect = new Expectation([]);
            $expect->toBe([]);
        });

        it('throws exception if not strictly equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation([]);
                $expect->toBe('');
            });
        });
    });

    context('notToBe', function() {
        it('returns if the value is not strictly equal', function() {
            $expect = new Expectation([]);
            $expect->notToBe('');
        });

        it('throws exception if strictly equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation([]);
                $expect->notToBe([]);
            });
        });
    });

    context('toEqual', function() {
        it('returns if the value is strictly equal', function() {
            $expect = new Expectation('');
            $expect->toBe('');
        });

        it('throws exception if not strictly equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation('');
                $expect->toBe(null);
            });
        });
    });

    context('notToEqual', function() {
        it('returns if the value is not strictly equal', function() {
            $expect = new Expectation([]);
            $expect->notToEqual('');
        });

        it('throws exception if strictly equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation([]);
                $expect->notToEqual([]);
            });
        });
    });

    context('toBeNull', function() {
        it('returns if the value is null', function() {
            $expect = new Expectation(null);
            $expect->toBeNull();
        });

        it('throws exception if not null', function() {
            shouldThrowException(function() {
                $expect = new Expectation('');
                $expect->toBeNull();
            });
        });
    });

    context('notToBeNull', function() {
        it('returns if the value is not null', function() {
            $expect = new Expectation('');
            $expect->notToBeNull();
        });

        it('throws exception if null', function() {
            shouldThrowException(function() {
                $expect = new Expectation(null);
                $expect->notToBeNull();
            });
        });
    });

    context('toBeTrue', function() {
        it('returns if the value is true', function() {
            $expect = new Expectation(true);
            $expect->toBeTrue();
        });

        it('throws exception if not true', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->toBeTrue();
            });
        });
    });

    context('notToBeTrue', function() {
        it('returns if the value is not true', function() {
            $expect = new Expectation(1);
            $expect->notToBeTrue();
        });

        it('throws exception if true', function() {
            shouldThrowException(function() {
                $expect = new Expectation(true);
                $expect->notToBeTrue();
            });
        });
    });

    context('toBeFalse', function() {
        it('returns if the value is false', function() {
            $expect = new Expectation(false);
            $expect->toBeFalse();
        });

        it('throws exception if not false', function() {
            shouldThrowException(function() {
                $expect = new Expectation(0);
                $expect->toBeFalse();
            });
        });
    });

    context('notToBeFalse', function() {
        it('returns if the value is not false', function() {
            $expect = new Expectation(0);
            $expect->notToBeFalse();
        });

        it('throws exception if false', function() {
            shouldThrowException(function() {
                $expect = new Expectation(false);
                $expect->notToBeFalse();
            });
        });
    });

    context('toEql', function() {
        it('returns if the value is loosely equal', function() {
            $expect = new Expectation(0);
            $expect->toEql(false);
        });

        it('throws exception if not loosely equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->toEql(false);
            });
        });
    });

    context('notToEql', function() {
        it('returns if the value is not loosely equal', function() {
            $expect = new Expectation(1);
            $expect->notToEql(false);
        });

        it('throws exception if loosely equal', function() {
            shouldThrowException(function() {
                $expect = new Expectation(0);
                $expect->notToEql(false);
            });
        });
    });

    context('toBeEmpty', function() {
        it('returns if the length of an array is 0', function() {
            $expect = new Expectation([]);
            $expect->toBeEmpty();
        });

        it('throws exception if the length of an array is not 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['']);
                $expect->toBeEmpty();
            });
        });

        it('returns if the length of a string is 0', function() {
            $expect = new Expectation('');
            $expect->toBeEmpty();
        });

        it('throws exception if the length of a string is not 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation('0');
                $expect->toBeEmpty();
            });
        });
    });

    context('notToBeEmpty', function() {
        it('returns if the length of an array is not 0', function() {
            $expect = new Expectation(['']);
            $expect->notToBeEmpty();
        });

        it('throws exception if the length of an array is 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation([]);
                $expect->notToBeEmpty();
            });
        });

        it('returns if the length of a string is not 0', function() {
            $expect = new Expectation('0');
            $expect->notToBeEmpty();
        });

        it('throws exception if the length of a string is 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation('');
                $expect->notToBeEmpty();
            });
        });
    });

    context('toContain', function() {
        context('with a single argument', function() {
            it('returns if the array contains the value', function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->toContain('spec');
            });

            it('throws exception if the value is not included', function() {
                shouldThrowException(function() {
                    $expect = new Expectation(['test', 'spec']);
                    $expect->toContain('bdd');
                });
            });

            it('returns if the string contains the substring', function() {
                $expect = new Expectation('testing');
                $expect->toContain('test');
            });

            it('throws exception if it the substring is not included', function() {
                shouldThrowException(function() {
                    $expect = new Expectation('testing');
                    $expect->toContain('TEST');
                });
            });
        });

        context('with multiple arguments', function() {
            it('returns if the array contains all values', function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->toContain('spec', 'test');
            });

            it('throws exception if not all values are included', function() {
                shouldThrowException(function() {
                    $expect = new Expectation(['test', 'spec']);
                    $expect->toContain('test', 'bdd');
                });
            });

            it('returns if the string contains the substring', function() {
                $expect = new Expectation('testing');
                $expect->toContain('test', 'ing');
            });

            it('throws exception if not all substrings are included', function() {
                shouldThrowException(function() {
                    $expect = new Expectation('testing');
                    $expect->toContain('test', 'ing', 'TEST');
                });
            });
        });
    });

    context('notToContain', function() {
        context('with a single argument', function() {
            it('returns if the array does not contain the value', function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->notToContain('bdd');
            });

            it('throws exception if the array contains the value', function() {
                shouldThrowException(function() {
                    $expect = new Expectation(['test', 'spec']);
                    $expect->notToContain('spec');
                });
            });

            it('returns if the string does not contain the substring', function() {
                $expect = new Expectation('testing');
                $expect->notToContain('TEST');
            });

            it('throws exception if it contains the substring', function() {
                shouldThrowException(function() {
                    $expect = new Expectation('testing');
                    $expect->notToContain('test');
                });
            });
        });

        context('with multiple arguments', function() {
            it('returns if the array does not contain any of the values', function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->notToContain('bdd', 'tests');
            });

            it('throws exception if any value is included', function() {
                shouldThrowException(function() {
                    $expect = new Expectation(['test', 'spec']);
                    $expect->notToContain('bdd', 'spec');
                });
            });

            it('returns if the string does not include any substring', function() {
                $expect = new Expectation('testing');
                $expect->notToContain('TEST', 'ING');
            });

            it('throws exception if it includes a substring', function() {
                shouldThrowException(function() {
                    $expect = new Expectation('testing');
                    $expect->notToContain('tests', 'test');
                });
            });
        });
    });

    context('toPrint', function() {
        it('returns if callable printed the value', function() {
            $expect = new Expectation(function() {
                echo 'test';
            });
            $expect->toPrint('test');
        });

        it('throws exception if callable does not print the value', function() {
            shouldThrowException(function() {
                $expect = new Expectation(function() {
                    echo 'testing';
                });
                $expect->toPrint('test');
            });
        });
    });

    context('notToPrint', function() {
        it('throws exception if callable printed the value', function() {
            shouldThrowException(function() {
                $expect = new Expectation(function() {
                    echo 'test';
                });
                $expect->notToPrint('test');
            });
        });

        it('returns if callable does not print the value', function() {
            $expect = new Expectation(function() {
                echo 'testing';
            });
            $expect->notToPrint('test');
        });
    });

    context('toThrow', function() {
        it('returns if callable threw the exception', function() {
            $expect = new Expectation(function() {
                throw new \Exception('test');
            });
            $expect->toThrow('\Exception');
        });

        it('throws exception if callable throws a different exception', function() {
            shouldThrowException(function() {
                $expect = new Expectation(function() {
                    throw new \Exception('test');
                });
                $expect->toThrow('\ErrorException');
            });
        });

        it('throws exception if callable does not throw an exception', function() {
            shouldThrowException(function() {
                $expect = new Expectation(function() {
                    return;
                });
                $expect->toThrow('\Exception');
            });
        });
    });

    context('notToThrow', function() {
        it('throws an exception if the callable threw the exception', function() {
            shouldThrowException(function() {
                $expect = new Expectation(function() {
                    throw new \Exception('test');
                });
                $expect->notToThrow('\Exception');
            });
        });

        it('returns if callable throws a different exception', function() {
            $expect = new Expectation(function() {
                throw new \Exception('test');
            });
            $expect->notToThrow('\ErrorException');
        });

        it('returns if callable does not throw an exception', function() {
            $expect = new Expectation(function() {
                return;
            });
            $expect->notToThrow('\Exception');
        });
    });

    context('toMatch', function() {
        it('returns if the string matches the pattern', function() {
            $expect = new Expectation('user123');
            $expect->toMatch('/\w{4}123/');
        });

        it('throws exception if it does not match the pattern', function() {
            shouldThrowException(function() {
                $expect = new Expectation('1');
                $expect->toMatch('/\d{2}/');
            });
        });
    });

    context('notToMatch', function() {
        it('returns if the string does not match the pattern', function() {
            $expect = new Expectation('123');
            $expect->notToMatch('/test\d*/');
        });

        it('throws exception if it matches the pattern', function() {
            shouldThrowException(function() {
                $expect = new Expectation('123');
                $expect->notToMatch('/\d*/');
            });
        });
    });

    context('toStartWith', function() {
        it('returns if the string contains the prefix', function() {
            $expect = new Expectation('test123');
            $expect->toStartWith('test');
        });

        it('throws exception if it does not contain the prefix', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test123');
                $expect->toStartWith('123');
            });
        });
    });

    context('notToStartWith', function() {
        it('returns if the string does not contain the prefix', function() {
            $expect = new Expectation('test123');
            $expect->notToStartWith('123');
        });

        it('throws exception if it contains the prefix', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test123');
                $expect->notToStartWith('test');
            });
        });
    });

    context('toEndWith', function() {
        it('returns if the string contains the suffix', function() {
            $expect = new Expectation('test123');
            $expect->toEndWith('123');
        });

        it('throws exception if it does not contain the suffix', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test123');
                $expect->toEndWith('test');
            });
        });
    });

    context('notToEndWith', function() {
        it('returns if the string does not contain the suffix', function() {
            $expect = new Expectation('test123');
            $expect->notToEndWith('test');
        });

        it('throws exception if it contains the suffix', function() {
            shouldThrowException(function() {
                $expect = new Expectation('test123');
                $expect->notToEndWith('123');
            });
        });
    });

    context('toBeGreaterThan', function() {
        it('returns if the value is greater than the min', function() {
            $expect = new Expectation(2);
            $expect->toBeGreaterThan(1);
        });

        it('throws exception if it is not greater than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->toBeGreaterThan(2);
            });
        });
    });

    context('notToBeGreaterThan', function() {
        it('returns if the value is not greater than the min', function() {
            $expect = new Expectation(1);
            $expect->notToBeGreaterThan(2);
        });

        it('throws exception if the value is greater than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(2);
                $expect->notToBeGreaterThan(1);
            });
        });
    });

    context('toBeAbove', function() {
        it('returns if the value is greater than the min', function() {
            $expect = new Expectation(2);
            $expect->toBeAbove(1);
        });

        it('throws exception if it is not greater than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->toBeAbove(2);
            });
        });
    });

    context('notToBeAbove', function() {
        it('returns if the value is not greater than the min', function() {
            $expect = new Expectation(1);
            $expect->notToBeAbove(2);
        });

        it('throws exception if the value is greater than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(2);
                $expect->notToBeAbove(1);
            });
        });
    });

    context('toBeLessThan', function() {
        it('returns if the value is less than the max', function() {
            $expect = new Expectation(1);
            $expect->toBeLessThan(2);
        });

        it('throws exception if it is not less than the max', function() {
            shouldThrowException(function() {
                $expect = new Expectation(2);
                $expect->toBeLessThan(1);
            });
        });
    });

    context('notToBeLessThan', function() {
        it('returns if the value is not less than the min', function() {
            $expect = new Expectation(2);
            $expect->notToBeLessThan(1);
        });

        it('throws exception if the value is less than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->notToBeLessThan(2);
            });
        });
    });

    context('toBeBelow', function() {
        it('returns if the value is less than the max', function() {
            $expect = new Expectation(1);
            $expect->toBeBelow(2);
        });

        it('throws exception if it is not less than the max', function() {
            shouldThrowException(function() {
                $expect = new Expectation(2);
                $expect->toBeBelow(1);
            });
        });
    });

    context('notToBeBelow', function() {
        it('returns if the value is not less than the min', function() {
            $expect = new Expectation(2);
            $expect->notToBeBelow(1);
        });

        it('throws exception if the value is less than the min', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1);
                $expect->notToBeBelow(2);
            });
        });
    });

    context('toBeWithin', function() {
        it('returns if the value is within the range', function() {
            $expect = new Expectation(1);
            $expect->toBeWithin(1, 2);
        });

        it('throws exception if it is not within the range', function() {
            shouldThrowException(function() {
                $expect = new Expectation(1.1);
                $expect->toBeWithin(0, 1);
            });
        });
    });

    context('notToBeWithin', function() {
        it('returns if the value is not within the range', function() {
            $expect = new Expectation(2);
            $expect->notToBeWithin(1, 1.9);
        });

        it('throws exception if the value is within the range', function() {
            shouldThrowException(function() {
                $expect = new Expectation(-1);
                $expect->notToBeWithin(-10, 10);
            });
        });
    });

    context('toHaveKey', function() {
        it('returns if the array has the key', function() {
            $expect = new Expectation(['test' => 'value']);
            $expect->toHaveKey('test');
        });

        it('throws exception if the array does not have the key', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['test' => 'value']);
                $expect->toHaveKey('invalid');
            });
        });
    });

    context('notToHaveKey', function() {
        it('returns if the array does not have the key', function() {
            $expect = new Expectation(['test' => 'value']);
            $expect->notToHaveKey('randomkey');
        });

        it('throws exception if the array has the key', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['value']);
                $expect->notToHaveKey(0);
            });
        });
    });

    context('custom matchers', function() {
        it('can be added with addMatcher', function() {
            shouldThrowException(function() {
                Expectation::addMatcher('toTest',
                    'pho\spec\Expectation\Matcher\MockMatcher');
                $expect = new Expectation('a');
                $expect->toTest('b');
            });

            $expect = new Expectation('a');
            $expect->toTest('a');
        });

        it('can be called in their negated form', function() {
            shouldThrowException(function() {
                Expectation::addMatcher('toTest',
                    'pho\spec\Expectation\Matcher\MockMatcher');
                $expect = new Expectation('a');
                $expect->notToTest('a');
            });

            $expect = new Expectation('a');
            $expect->notToTest('b');
        });
    });

    it('throws a BadMethodCallException with an invalid method', function() {
        shouldThrowException(function() {
            $expect = new Expectation('test');
            $expect->invalidMethod();
        }, '\\BadMethodCallException');
    });

    it('throws a BadMethodCallException with an invalid negated method', function() {
        shouldThrowException(function() {
            $expect = new Expectation('test');
            $expect->notInvalidMethod();
        }, '\\BadMethodCallException');
    });
});
