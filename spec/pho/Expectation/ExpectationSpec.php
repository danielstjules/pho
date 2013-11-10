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
});
