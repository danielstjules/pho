<?php

use pho\Exception\Exception;
use pho\Exception\ErrorException;

describe('ErrorException', function() {
    $exception = new ErrorException(E_ERROR, 'test', 'testfile', '1');

    it('extends Exception', function() use ($exception) {
        expect($exception instanceof Exception)->toBeTrue();
    });

    it('uses the error level as the type', function() use ($exception) {
        expect($exception->getType())->toEqual('E_ERROR');
    });
});
