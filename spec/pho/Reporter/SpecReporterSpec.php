<?php

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\SpecReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

describe('SpecReporter', function() {
    before(function() {
        $console = new Console([]);
        $console->parseArguments();
        $this->set('console', $console);

        $suite = new Suite('test', function(){});
        $spec = new Spec('testspec', function(){}, $suite);
        $this->set('spec', $spec);
    });

    it('implements the ReporterInterface', function() {
        $reporter = new SpecReporter($this->get('console'));
        expect($reporter instanceof ReporterInterface)->toBeTrue();
    });

    context('beforeSuite', function() {
        before(function() {
            $reporter = new SpecReporter($this->get('console'));
            $this->set('reporter', $reporter);
        });

        it('prints the suite title', function() {
            $beforeSuite = function() {
                $suite = new Suite('test suite', function() {});
                $reporter = $this->get('reporter');
                $reporter->beforeSuite($suite);
            };

            expect($beforeSuite)->toPrint('test suite' . PHP_EOL);
        });

        it('pads nested suites', function() {
            $beforeSuite = function() {
                $suite = new Suite('test suite', function() {});
                $reporter = $this->get('reporter');
                $reporter->beforeSuite($suite);
            };

            expect($beforeSuite)->toPrint('    test suite' . PHP_EOL);
        });
    });

    context('beforeSpec', function() {
        it('increments the spec count', function() {
            $reporter = new SpecReporter($this->get('console'));

            $countBefore = $reporter->getSpecCount();
            $reporter->beforeSpec($this->get('spec'));
            $countAfter = $reporter->getSpecCount();

            expect($countAfter)->toEqual($countBefore + 1);
        });
    });

    context('afterSpec', function() {
        it('prints the spec title in grey if it passed', function() {
            $reporter = new SpecReporter($this->get('console'));
            $afterSpec = function() use ($reporter) {
                $reporter->afterSpec($this->get('spec'));
            };

            $console = $this->get('console');
            $specTitle = $console->formatter->grey($this->get('spec')->title);
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });

        it('prints the spec title in red if it failed', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function() {
                throw new \Exception('test');
            }, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new SpecReporter($this->get('console'));
                $reporter->afterSpec($spec);
            };

            $console = $this->get('console');
            $specTitle = $console->formatter->red($this->get('spec')->title);
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });
    });
});
