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

        context('when the help flag is used', function() {
            before(function() {
                $console = new Console(['--help']);

                ob_start();
                $console->parseArguments();
                $this->set('printContents', ob_get_contents());
                ob_end_clean();

                $this->set('console', $console);
            });

            it('sets the error status to 0', function() {
                expect($this->get('console')->getErrorStatus())->toEqual(0);
            });

            it('prints the option list and help', function() {
                expect($this->get('printContents'))
                    ->toContain('Usage: pho [options] [files]')
                    ->toContain('Options')
                    ->toContain('help');
            });
        });

        context('when the version flag is used', function() {
            before(function() {
                $console = new Console(['--version']);

                ob_start();
                $console->parseArguments();
                $this->set('printContents', ob_get_contents());
                ob_end_clean();

                $this->set('console', $console);
            });

            it('sets the error status to 0', function() {
                expect($this->get('console')->getErrorStatus())->toEqual(0);
            });

            it('prints version info', function() {
                expect($this->get('printContents'))
                    ->toEqual('pho version 0.0.1' . PHP_EOL);
            });
        });

        context('when an invalid option is passed', function() {
            before(function() {
                $console = new Console(['--invalid']);

                ob_start();
                $console->parseArguments();
                $this->set('printContents', ob_get_contents());
                ob_end_clean();

                $this->set('console', $console);
            });

            it('sets the error status to 1', function() {
                expect($this->get('console')->getErrorStatus())->toEqual(1);
            });

            it('lists the invalid option', function() {
                expect($this->get('printContents'))
                    ->toEqual('--invalid is not a valid option' . PHP_EOL);
            });
        });

        context('when an invalid path is used', function() {
            before(function() {
                $console = new Console(['./someinvalidpath']);

                ob_start();
                $console->parseArguments();
                $this->set('printContents', ob_get_contents());
                ob_end_clean();

                $this->set('console', $console);
            });

            it('sets the error status to 1', function() {
                expect($this->get('console')->getErrorStatus())->toEqual(1);
            });

            it('lists the invalid path', function() {
                expect($this->get('printContents'))->toEqual(
                    "The file or path \"./someinvalidpath\" doesn't exist" . PHP_EOL);
            });
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
        it('prints the text to the terminal', function() {
            $write = function() {
                $console = new Console([]);
                $console->write('test');
            };
            expect($write)->toPrint('test');
        });
    });

    context('writeLn', function() {
        it('prints the text, followed by a newline, to the terminal', function() {
            $writeLn = function() {
                $console = new Console([]);
                $console->writeLn('test');
            };
            expect($writeLn)->toPrint('test' . PHP_EOL);
        });
    });
});
