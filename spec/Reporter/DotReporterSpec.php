<?php

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\DotReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

describe('DotReporter', function() {
    before(function() {
        $console = new Console([], 'php://output');
        $console->parseArguments();
        $this->console = $console;

        $reporter = new DotReporter($console);
        $this->reporter = $reporter;

        $suite = new Suite('test', function(){});
        $spec = new Spec('testspec', function(){}, $suite);
        $this->spec = $spec;
    });

    it('implements the ReporterInterface', function() {
        expect($this->reporter instanceof ReporterInterface)->toBe(true);
    });

    context('beforeSpec', function() {
        it('increments the spec count', function() {
            $reporter = $this->reporter;

            $countBefore = $reporter->getSpecCount();
            $reporter->beforeSpec($this->spec);
            $countAfter = $reporter->getSpecCount();

            expect($countAfter)->toEqual($countBefore + 1);
        });

        it('prints a newline after a limit', function() {
            $print = function() {
                $reporter = $this->reporter;
                $spec = $this->spec;

                for ($i = 0; $i <= 60; $i++) {
                    $reporter->beforeSpec($spec);
                    $reporter->afterSpec($spec);
                }
            };

            // TODO: Add pattern matching to toPrint, use '/.*\n/'
            $expected = '...................................................' .
                        '.........' . PHP_EOL . '.';
            expect($print)->toPrint($expected);
        });
    });

    context('afterSpec', function() {
        it('prints a dot if the spec passed', function() {
            $reporter = $this->reporter;
            $afterSpec = function() {
                $this->reporter->afterSpec($this->spec);
            };

            expect($afterSpec)->toPrint('.');
        });

        it('prints an F in red if a spec failed', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function() {
                throw new \Exception('test');
            }, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $this->reporter->afterSpec($spec);
            };

            $console = $this->console;
            expect($afterSpec)->toPrint($console->formatter->red('F'));
        });

        it('prints an I in cyan if incomplete', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $this->reporter->afterSpec($spec);
            };

            $console = $this->console;
            expect($afterSpec)->toPrint($console->formatter->cyan('I'));
        });

        it('prints an P in yellow if pending', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->setPending();
            $spec->run();

            $afterSpec = function() use ($spec) {
                $this->reporter->afterSpec($spec);
            };

            $console = $this->console;
            expect($afterSpec)->toPrint($console->formatter->yellow('P'));
        });
    });
});
