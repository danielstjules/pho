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

    context('toEql', function() {
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
});
