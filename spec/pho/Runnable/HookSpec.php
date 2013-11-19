<?php

use pho\Suite\Suite;
use pho\Runnable\Hook;
use pho\Runnable\Runnable;

describe('Hook', function() {
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
            $hook = new Hook($closure, $this->get('suite'));
            $hook->run();
        };

        expect($run)->toPrint('testvalue');
    });
});
