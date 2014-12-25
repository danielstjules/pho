<?php

use pho\Exception\ExpectationException;

describe('ExpectationException', function() {
    $exception = new ExpectationException('test message');

    it('extends \Exception', function() use ($exception) {
        expect($exception instanceof \Exception)->toBe(true);
    });

    it('contains the message on toString', function() use ($exception) {
        expect((string) $exception)->toContain('test message');
    });
});
