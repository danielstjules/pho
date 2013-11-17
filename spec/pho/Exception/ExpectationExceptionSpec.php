<?php

use pho\Exception\ExpectationException;

describe('ExpectationException', function() {
    $exception = new ExpectationException('test message');

    it('extends \Exception', function() use ($exception) {
        expect($exception instanceof \Exception)->toBeTrue();
    });

    it('returns the message on toString', function() use ($exception) {
        expect($exception)->toEql('test message');
    });
});
