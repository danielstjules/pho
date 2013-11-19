<?php

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\DotReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

describe('DotReporter', function() {
    before(function() {
        $console = new Console([]);
        $console->parseArguments();
        $this->set('console', $console);

        $reporter = new DotReporter($console);
        $this->set('reporter', $reporter);

        $suite = new Suite('test', function(){});
        $spec = new Spec('testspec', function(){}, $suite);
        $this->set('spec', $spec);
    });

    it('implements the ReporterInterface', function() {
        expect($this->get('reporter') instanceof ReporterInterface)->toBeTrue();
    });

    context('beforeSpec', function() {
        it('increments the spec count', function() {
            $reporter = $this->get('reporter');

            $countBefore = $reporter->getSpecCount();
            $reporter->beforeSpec($this->get('spec'));
            $countAfter = $reporter->getSpecCount();

            expect($countAfter)->toEqual($countBefore + 1);
        });

        it('prints a newline after a limit', function() {
            $print = function() {
                $reporter = $this->get('reporter');
                $spec = $this->get('spec');

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
            $reporter = $this->get('reporter');
            $afterSpec = function() {
                $this->get('reporter')->afterSpec($this->get('spec'));
            };

            expect($afterSpec)->toPrint('.');
        });

        it('prints an F if a spec failed', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function() {
                throw new \Exception('test');
            }, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $this->get('reporter')->afterSpec($spec);
            };

            $console = $this->get('console');
            expect($afterSpec)->toPrint($console->formatter->red('F'));
        });
    });
});