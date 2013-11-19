<?php

namespace spec;

use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Runnable;
use pho\Exception\ErrorException;
use pho\Exception\ExpectationException;

class MockRunnable extends Runnable
{
    public function __construct(\Closure $closure, Suite $suite)
    {
        $this->suite = $suite;
        $this->closure = $closure->bindTo($suite);
    }
}

describe('Runnable', function() {
    before(function() {
        $this->set('suite', new Suite('TestSuite', function() {}));
    });

    context('run', function() {
        it('catches and stores errors', function() {
            $closure = function() {
                trigger_error('TestError', E_USER_NOTICE);
            };
            $runnable = new MockRunnable($closure, $this->get('suite'));
            $runnable->run();

            expect($runnable->exception->getType())->toEqual('E_USER_NOTICE');
        });

        it('catches and stores ExpectationExceptions', function() {
            $closure = function() {
                throw new ExpectationException('test');
            };
            $runnable = new MockRunnable($closure, $this->get('suite'));
            $runnable->run();

            expect($runnable->exception->getMessage())->toEqual('test');
        });

        it('catches and stores all other exceptions', function() {
            $closure = function() {
                throw new \Exception('test exception');
            };
            $runnable = new MockRunnable($closure, $this->get('suite'));
            $runnable->run();

            expect($runnable->exception->getMessage())->toEqual('test exception');
        });
    });
});
