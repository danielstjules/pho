<?php

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\SpecReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

describe('SpecReporter', function() {
    before(function() {
        $console = new Console([], 'php://output');
        $console->parseArguments();
        $this->console = $console;

        $suite = new Suite('test', function(){});
        $spec = new Spec('testspec', function(){}, $suite);
        $this->spec = $spec;
    });

    it('implements the ReporterInterface', function() {
        $reporter = new SpecReporter($this->console);
        expect($reporter instanceof ReporterInterface)->toBe(true);
    });

    context('beforeSuite', function() {
        before(function() {
            $reporter = new SpecReporter($this->console);
            $this->reporter = $reporter;
        });

        it('prints the suite title', function() {
            $beforeSuite = function() {
                $suite = new Suite('test suite', function() {});
                $reporter = $this->reporter;
                $reporter->beforeSuite($suite);
            };

            expect($beforeSuite)->toPrint(PHP_EOL . "test suite" . PHP_EOL);
        });

        it('pads nested suites', function() {
            $beforeSuite = function() {
                $suite = new Suite('test suite', function() {});
                $reporter = $this->reporter;
                $reporter->beforeSuite($suite);
            };

            expect($beforeSuite)->toPrint("    test suite" . PHP_EOL);
        });
    });

    context('beforeSpec', function() {
        it('increments the spec count', function() {
            $reporter = new SpecReporter($this->console);

            $countBefore = $reporter->getSpecCount();
            $reporter->beforeSpec($this->spec);
            $countAfter = $reporter->getSpecCount();

            expect($countAfter)->toEqual($countBefore + 1);
        });
    });

    context('afterSpec', function() {
        it('prints the spec title in grey if it passed', function() {
            $reporter = new SpecReporter($this->console);
            $afterSpec = function() use ($reporter) {
                $reporter->afterSpec($this->spec);
            };

            $console = $this->console;
            $title = $this->console->formatter->grey($this->spec->getTitle());
            expect($afterSpec)->toPrint($title . PHP_EOL);
        });

        it('prints the spec title in red if it failed', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function() {
                throw new \Exception('test');
            }, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new SpecReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->red($this->spec->getTitle());
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });

        it('prints the spec title in cyan if incomplete', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new SpecReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->cyan($this->spec->getTitle());
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });

        it('prints the spec title in yellow if pending', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->setPending();
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new SpecReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->yellow($this->spec->getTitle());
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });
    });
});
