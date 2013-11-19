<?php

use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Runnable;

describe('Spec', function() {
    before(function() {
        $this->set('suite', new Suite('TestSuite', function() {}));
    });

    it('has its closure bound to the suite', function() {
        $suite = $this->get('suite');
        $suite->set('key', 'testvalue');

        $run = function() {
            $closure = function() {
                echo $this->get('key');
            };
            $spec = new Spec('spec', $closure, $this->get('suite'));
            $spec->run();
        };

        expect($run)->toPrint('testvalue');
    });

    context('passed', function() {
        it('returns true if no exception was thrown', function() {
            $closure = function() {};
            $spec = new Spec('spec', $closure, $this->get('suite'));
            $spec->run();

            expect($spec->passed())->toBeTrue();
        });

        it('returns false if an exception was thrown', function() {
            $closure = function() {
                throw new \Exception('exception');
            };
            $spec = new Spec('spec', $closure, $this->get('suite'));
            $spec->run();

            expect($spec->passed())->toBeFalse();
        });
    });

    context('__toString', function() {
        it('returns the suite title followed by the spec title', function() {
            $closure = function() {};
            $spec = new Spec('SpecTitle', $closure, $this->get('suite'));

            expect((string) $spec)->toEqual('TestSuite SpecTitle');
        });
    });
});
