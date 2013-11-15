<?php

use pho\Console\Console;

describe('Console', function() {
    context('parseArguments', function() {
        it('parses the arguments with the available options', function() {
            $console = new Console(['--reporter', 'dot', '-s']);
            $console->parseArguments();

            expect($console->options)->toEqual([
                'ascii'    => false,
                'help'     => false,
                'filter'   => false,
                'reporter' => 'dot',
                'stop'     => true,
                'version'  => false,
                'watch'    => false
            ]);
        });
    });

    context('getPaths', function() {
        it('returns the array of parsed paths', function() {
            $console = new Console(['./']);
            $console->parseArguments();

            expect($console->getPaths())->toEqual(['./']);
        });
    });

    context('getReporterClass', function() {
        it('returns DotReporter by default', function() {
            $console = new Console([]);
            $console->parseArguments();

            $expectedClass = 'pho\Reporter\DotReporter';
            expect($console->getReporterClass())->toEqual($expectedClass);
        });

        it('returns a valid reporter specified in the args', function() {
            $console = new Console(['-r', 'spec']);
            $console->parseArguments();

            $expectedClass = 'pho\Reporter\SpecReporter';
            expect($console->getReporterClass())->toEqual($expectedClass);
        });
    });

    context('write', function() {
        it('Prints the text to the terminal', function() {
            $write = function() {
                $console = new Console([]);
                $console->write('test');
            };
            expect($write)->toPrint('test');
        });
    });

    context('writeLn', function() {
        it('Prints the text, followed by a newline, to the terminal', function() {
            $writeLn = function() {
                $console = new Console([]);
                $console->writeLn('test');
            };
            expect($writeLn)->toPrint('test' . PHP_EOL);
        });
    });
});
