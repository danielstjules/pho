<?php

namespace spec;

use pho\Expectation\Expectation;
use pho\Exception\ExpectationException;

// Define some helpers for testing the expectations
function shouldReturn($closure) {
    $exceptionThrown = false;
    try {
        $closure();
    } catch (ExpectationException $exception) {
        $exceptionThrown = true;
    }

    if ($exceptionThrown) {
        throw new \Exception('Throws an exception');
    }
};

function shouldThrowException($closure) {
    $exceptionThrown = false;
    try {
        $closure();
    } catch (ExpectationException $exception) {
        $exceptionThrown = true;
    }

    if (!$exceptionThrown) {
        throw new \Exception('Does not throw exception');
    }
}

describe('Expectation', function() {
    context('toBeA', function() {
        it('returns if the value has the expected type', function() {
            shouldReturn(function() {
                $expect = new Expectation('test');
                $expect->toBeA('string');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->notToBeA('string');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(123);
                $expect->toBeAn('integer');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('test');
                $expect->notToBeAn('integer');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(new \stdClass());
                $expect->toBeAnInstanceOf('stdClass');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(new \stdClass());
                $expect->notToBeAnInstanceOf('Closure');
            });
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
            shouldReturn(function() {
                $expect = new Expectation([]);
                $expect->toBe([]);
            });
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
            shouldReturn(function() {
                $expect = new Expectation([]);
                $expect->notToBe('');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('');
                $expect->toBe('');
            });
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
            shouldReturn(function() {
                $expect = new Expectation([]);
                $expect->notToEqual('');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(null);
                $expect->toBeNull();
            });
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
            shouldReturn(function() {
                $expect = new Expectation('');
                $expect->notToBeNull();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(true);
                $expect->toBeTrue();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->notToBeTrue();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(false);
                $expect->toBeFalse();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(0);
                $expect->notToBeFalse();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(0);
                $expect->toEql(false);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->notToEql(false);
            });
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
            shouldReturn(function() {
                $expect = new Expectation([]);
                $expect->toBeEmpty();
            });
        });

        it('throws exception if the length of an array is not 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['']);
                $expect->toBeEmpty();
            });
        });

        it('returns if the length of a string is 0', function() {
            shouldReturn(function() {
                $expect = new Expectation('');
                $expect->toBeEmpty();
            });
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
            shouldReturn(function() {
                $expect = new Expectation(['']);
                $expect->notToBeEmpty();
            });
        });

        it('throws exception if the length of an array is 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation([]);
                $expect->notToBeEmpty();
            });
        });

        it('returns if the length of a string is not 0', function() {
            shouldReturn(function() {
                $expect = new Expectation('0');
                $expect->notToBeEmpty();
            });
        });

        it('throws exception if the length of a string is 0', function() {
            shouldThrowException(function() {
                $expect = new Expectation('');
                $expect->notToBeEmpty();
            });
        });
    });

    context('toContain', function() {
        it('returns if the array contains the value', function() {
            shouldReturn(function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->toContain('spec');
            });
        });

        it('throws exception if the array does not contain the value', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->toContain('bdd');
            });
        });

        it('returns if the string contains the substring', function() {
            shouldReturn(function() {
                $expect = new Expectation('testing');
                $expect->toContain('test');
            });
        });

        it('throws exception if it does not contain the substring', function() {
            shouldThrowException(function() {
                $expect = new Expectation('testing');
                $expect->toContain('TEST');
            });
        });
    });

    context('notToContain', function() {
        it('returns if the array does not contain the value', function() {
            shouldReturn(function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->notToContain('bdd');
            });
        });

        it('throws exception if the array contains the value', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['test', 'spec']);
                $expect->notToContain('spec');
            });
        });

        it('returns if the string does not contain the substring', function() {
            shouldReturn(function() {
                $expect = new Expectation('testing');
                $expect->notToContain('TEST');
            });
        });

        it('throws exception if it contains the substring', function() {
            shouldThrowException(function() {
                $expect = new Expectation('testing');
                $expect->notToContain('test');
            });
        });
    });

    context('toPrint', function() {
        it('returns if callable printed the value', function() {
            shouldReturn(function() {
                $expect = new Expectation(function() {
                    echo 'test';
                });
                $expect->toPrint('test');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(function() {
                    echo 'testing';
                });
                $expect->notToPrint('test');
            });
        });
    });

    context('toMatch', function() {
        it('returns if the string matches the pattern', function() {
            shouldReturn(function() {
                $expect = new Expectation('user123');
                $expect->toMatch('/\w{4}123/');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('123');
                $expect->notToMatch('/test\d*/');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('test123');
                $expect->toStartWith('test');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('test123');
                $expect->notToStartWith('123');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('test123');
                $expect->toEndWith('123');
            });
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
            shouldReturn(function() {
                $expect = new Expectation('test123');
                $expect->notToEndWith('test');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(2);
                $expect->toBeGreaterThan(1);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->notToBeGreaterThan(2);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(2);
                $expect->toBeAbove(1);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->notToBeAbove(2);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->toBeLessThan(2);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(2);
                $expect->notToBeLessThan(1);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->toBeBelow(2);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(2);
                $expect->notToBeBelow(1);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(1);
                $expect->toBeWithin(1, 2);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(2);
                $expect->notToBeWithin(1, 1.9);
            });
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
            shouldReturn(function() {
                $expect = new Expectation(['test' => 'value']);
                $expect->toHaveKey('test');
            });
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
            shouldReturn(function() {
                $expect = new Expectation(['test' => 'value']);
                $expect->notToHaveKey('randomkey');
            });
        });

        it('throws exception if the array has the key', function() {
            shouldThrowException(function() {
                $expect = new Expectation(['value']);
                $expect->notToHaveKey(0);
            });
        });
    });
});
