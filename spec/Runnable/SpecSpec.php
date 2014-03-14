<?php

use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Runnable;

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
});
