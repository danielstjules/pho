<?php

use pho\Console\ConsoleOption;

describe('ConsoleOption', function() {
    $optInfo = [
        'longName'     => 'testLongName',
        'shortName'    => 'testShortName',
        'description'  => 'testDescription',
        'argumentName' => 'testArgumentName',
    ];

    $option = new ConsoleOption($optInfo['longName'], $optInfo['shortName'],
        $optInfo['description'], $optInfo['argumentName']);

    context('basic getters', function() use ($option, $optInfo) {
        it('return longName', function() use ($option, $optInfo) {
            expect($option->getLongName())->toBe($optInfo['longName']);
        });

        it('return shortName', function() use ($option, $optInfo) {
            expect($option->getShortName())->toBe($optInfo['shortName']);
        });

        it('return description', function() use ($option, $optInfo) {
            expect($option->getDescription())->toBe($optInfo['description']);
        });

        it('return argumentName', function() use ($option, $optInfo) {
            expect($option->getArgumentName())->toBe($optInfo['argumentName']);
        });

        it('return value', function() use ($option, $optInfo) {
            expect($option->getValue())->toBe(false);
        });
    });

    context('acceptArguments', function() {
        it('returns true if an argument name was defined', function () {
            $option = new ConsoleOption('sname', 'lname', 'desc', 'argname');
            expect($option->acceptsArguments())->toBe(true);
        });

        it('returns true if an argument name was not defined', function () {
            $option = new ConsoleOption('sname', 'lname', 'desc');
            expect($option->acceptsArguments())->toBe(false);
        });
    });

    context('setValue', function() {
        it('sets the value if the option accepts arguments', function() {
            $option = new ConsoleOption('sname', 'lname', 'desc', 'argname');
            $value = 'test';
            $option->setValue($value);
            expect($option->getValue())->toBe($value);
        });

        it('casts the value to boolean if the option does not', function() {
            $option = new ConsoleOption('sname', 'lname', 'desc');
            $value = 'test';
            $option->setValue($value);
            expect($option->getValue())->toBe(true);
        });
    });
});
