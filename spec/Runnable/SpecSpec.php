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
        context('when passed', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function(){}, $this->suite);
                $this->spec->run();
            });
            it('return true', function() {
                expect($this->spec->isPassed())->toBeTrue();
            });
        });
        context('when not passed', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function() {
                    throw new Exception('faild');
                }, $this->suite);
                $this->spec->run();
            });
            it('return false', function() {
                expect($this->spec->isPassed())->toBeFalse();
            });
        });
    });

    describe('isFailed', function() {
        context('when failed', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function() {
                    throw new Exception('faild');
                }, $this->suite);
                $this->spec->run();
            });
            it('return true', function() {
                expect($this->spec->isFailed())->toBeTrue();
            });
        });
        context('when not failed', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function(){}, $this->suite);
                $this->spec->run();
            });
            it('return false', function() {
                expect($this->spec->isFailed())->toBeFalse();
            });
        });
    });

    describe('isIncomplete', function() {
        context('when incomplete', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', null, $this->suite);
                $this->spec->run();
            });
            it('return true', function() {
                expect($this->spec->isIncomplete())->toBeTrue();
            });
        });
        context('when not incomplete', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function(){}, $this->suite);
                $this->spec->run();
            });
            it('return false', function() {
                expect($this->spec->isIncomplete())->toBeFalse();
            });
        });
    });

    describe('isPending', function() {
        context('when pending', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', null, $this->suite);
                $this->spec->setPending();
            });
            it('return true', function() {
                expect($this->spec->isPending())->toBeTrue();
            });
        });
        context('when not pending', function() {
            before(function() {
                $this->spec = new Spec('SpecTitle', function(){}, $this->suite);
                $this->spec->run();
            });
            it('return false', function() {
                expect($this->spec->isPending())->toBeFalse();
            });
        });
    });

});
