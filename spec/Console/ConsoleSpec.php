<?php

use pho\Console\Console;

describe('Console', function() {
    context('parseArguments', function() {
        it('parses the arguments with the available options', function() {
            $console = new Console(['--reporter', 'dot', '-s'], 'php://output');
            $console->parseArguments();

            expect($console->options)->toEqual([
                'ascii'     => false,
                'bootstrap' => false,
                'filter'    => false,
                'help'      => false,
                'namespace' => false,
                'reporter'  => 'dot',
                'stop'      => true,
                'version'   => false,
                'watch'     => false,
                'no-color'  => false
            ]);
        });

        context('when the help flag is used', function() {
            before(function() {
                $console = new Console(['--help'], 'php://output');

                ob_start();
                $console->parseArguments();
                $this->printContents = ob_get_contents();
                ob_end_clean();

                $this->console = $console;
            });

            it('sets the error status to 0', function() {
                expect($this->console->getExitStatus())->toEqual(0);
            });

            it('prints the option list and help', function() {
                expect($this->printContents)
                    ->toContain('Usage: pho [options] [files]')
                    ->toContain('Options')
                    ->toContain('help');
            });
        });

        context('when the version flag is used', function() {
            before(function() {
                $console = new Console(['--version'], 'php://output');

                ob_start();
                $console->parseArguments();
                $this->printContents = ob_get_contents();
                ob_end_clean();

                $this->console = $console;
            });

            it('sets the error status to 0', function() {
                expect($this->console->getExitStatus())->toEqual(0);
            });

            it('prints version info', function() {
                expect($this->printContents)
                    ->toMatch('/pho version \d.\d.\d/');
            });
        });

        context('when an invalid option is passed', function() {
            before(function() {
                $console = new Console(['--invalid'], 'php://output');

                ob_start();
                $console->parseArguments();
                $this->printContents = ob_get_contents();
                ob_end_clean();

                $this->console = $console;
            });

            it('sets the error status to 1', function() {
                expect($this->console->getExitStatus())->toEqual(1);
            });

            it('lists the invalid option', function() {
                expect($this->printContents)
                    ->toEqual('--invalid is not a valid option' . PHP_EOL);
            });
        });

        context('when an invalid path is used', function() {
            before(function() {
                $console = new Console(['./someinvalidpath'], 'php://output');

                ob_start();
                $console->parseArguments();
                $this->printContents = ob_get_contents();
                ob_end_clean();

                $this->console = $console;
            });

            it('sets the error status to 1', function() {
                expect($this->console->getExitStatus())->toEqual(1);
            });

            it('lists the invalid path', function() {
                expect($this->printContents)->toEqual(
                    "The file or path \"./someinvalidpath\" doesn't exist" . PHP_EOL);
            });
        });
    });

    context('getPaths', function() {
        it('returns the array of parsed paths', function() {
            $console = new Console(['./'], 'php://output');
            $console->parseArguments();

            expect($console->getPaths())->toEqual(['./']);
        });
    });

    context('getReporterClass', function() {
        it('returns DotReporter by default', function() {
            $console = new Console([], 'php://output');
            $console->parseArguments();

            $expectedClass = 'pho\Reporter\DotReporter';
            expect($console->getReporterClass())->toEqual($expectedClass);
        });

        it('returns a valid reporter specified in the args', function() {
            $console = new Console(['-r', 'spec'], 'php://output');
            $console->parseArguments();

            $expectedClass = 'pho\Reporter\SpecReporter';
            expect($console->getReporterClass())->toEqual($expectedClass);
        });
        context('when reporter not found', function() {
            before(function() {
                $this->console = new Console(['-r', 'unkown'], 'php://output');
                $this->console->parseArguments();
            });
            it('throw pho\Exception\ReporterNotFoundException exception', function() {
                expect(function() {
                    $this->console->getReporterClass();
                })->toThrow('pho\Exception\ReporterNotFoundException');
            });
        });
    });

    context('write', function() {
        it('prints the text to the terminal', function() {
            $write = function() {
                $console = new Console([], 'php://output');
                $console->write('test');
            };
            expect($write)->toPrint('test');
        });
    });

    context('writeLn', function() {
        it('prints the text, followed by a newline, to the terminal', function() {
            $writeLn = function() {
                $console = new Console([], 'php://output');
                $console->writeLn('test');
            };
            expect($writeLn)->toPrint('test' . PHP_EOL);
        });
    });
});
