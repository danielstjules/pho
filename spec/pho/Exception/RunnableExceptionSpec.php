<?php

use pho\Exception\Exception;
use pho\Exception\RunnableException;

describe('RunnableException', function() {
    $caught = new \Exception('test');
    $exception = new RunnableException($caught);

    it('extends Exception', function() use ($exception) {
        expect($exception instanceof Exception)->toBeTrue();
    });

    it('uses the exception class as the type', function() use ($exception) {
        expect($exception->getType())->toEqual('Exception');
    });
});
