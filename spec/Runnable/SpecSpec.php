<?php

use pho\Suite\Suite;
use pho\Runnable\Spec;

describe('Spec', function() {
    before(function() {
        $this->suite = new Suite('TestSuite', function() {});
    });

    it('has its closure bound to the suite', function() {
        $suite = $this->suite;
        $suite->key = 'testvalue';

        $run = function() {
            $closure = function() {
                echo $this->key;
            };
            $spec = new Spec('spec', $closure, $this->suite);
            $spec->run();
        };

        expect($run)->toPrint('testvalue');
    });

    context('getResult', function() {
        it('returns PASSED if no exception was thrown', function() {
            $closure = function() {};
            $spec = new Spec('spec', $closure, $this->suite);
            $spec->run();

            expect($spec->getResult())->toBe(Spec::PASSED);
        });

        it('returns FAILED if an exception was thrown', function() {
            $closure = function() {
                throw new \Exception('exception');
            };
            $spec = new Spec('spec', $closure, $this->suite);
            $spec->run();

            expect($spec->getResult())->toBe(Spec::FAILED);
        });

        it('returns INCOMPLETE if no closure was ran', function() {
            $spec = new Spec('spec', null, $this->suite);
            $spec->run();

            expect($spec->getResult())->toBe(Spec::INCOMPLETE);
        });

        it('returns PENDING if marked as pending', function() {
            $spec = new Spec('spec', null, $this->suite);
            $spec->setPending();
            $spec->run();

            expect($spec->getResult())->toBe(Spec::PENDING);
        });
    });

    context('__toString', function() {
        it('returns the suite title followed by the spec title', function() {
            $closure = function() {};
            $spec = new Spec('SpecTitle', $closure, $this->suite);

            expect((string) $spec)->toEqual('TestSuite SpecTitle');
        });
    });

    describe('isPassed', function() {
        it('returns true when the spec passed', function() {
            $spec = new Spec('SpecTitle', function(){}, $this->suite);
            $spec->run();

            expect($spec->isPassed())->toBe(true);
        });

        it('returns false when the spec did not pass', function() {
            $spec = new Spec('SpecTitle', function() {
                throw new Exception('failed');
            }, $this->suite);
            $spec->run();

            expect($spec->isPassed())->toBe(false);
        });
    });

    describe('isFailed', function() {
        it('returns true when the spec failed', function() {
            $spec = new Spec('SpecTitle', function() {
                throw new Exception('failed');
            }, $this->suite);
            $spec->run();

            expect($spec->isFailed())->toBe(true);
        });

        it('returns false when the spec did not fail', function() {
            $spec = new Spec('SpecTitle', function(){}, $this->suite);
            $spec->run();

            expect($spec->isFailed())->toBe(false);
        });
    });

    describe('isIncomplete', function() {
        it('returns true when the spec is incomplete', function() {
            $spec = new Spec('SpecTitle', null, $this->suite);
            $spec->run();

            expect($spec->isIncomplete())->toBe(true);
        });

        it('returns false when the spec is not incomplete', function() {
            $spec = new Spec('SpecTitle', function(){}, $this->suite);
            $spec->run();

            expect($spec->isIncomplete())->toBe(false);
        });
    });

    describe('isPending', function() {
        it('returns true when the spec is pending', function() {
            $spec = new Spec('SpecTitle', null, $this->suite);
            $spec->setPending();

            expect($spec->isPending())->toBe(true);
        });

        it('returns false when the spec is not pending', function() {
            $spec = new Spec('SpecTitle', function(){}, $this->suite);
            $spec->run();

            expect($spec->isPending())->toBe(false);
        });
    });

    describe('setException', function() {
        it('accepts a null value', function() {
            $spec = new Spec('spec', null, $this->suite);
            $spec->setException(null);
            expect($spec->getException())->toBe(null);
        });

        it('updates the stored exception', function() {
            $error = new \Exception('foo');
            $spec = new Spec('spec', null, $this->suite);
            $spec->setException($error);
            expect($spec->getException())->toBe($error);
        });

        it('updates the spec result to FAILED', function() {
            $spec = new Spec('spec', null, $this->suite);
            $spec->setException(new \Exception('foo'));
            expect($spec->getResult())->toBe(Spec::FAILED);
        });
    });
});
