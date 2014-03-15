<?php

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\ListReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

describe('ListReporter', function() {
    before(function() {
        $console = new Console([], 'php://output');
        $console->parseArguments();
        $this->console = $console;

        $suite = new Suite('test', function(){});
        $spec = new Spec('testspec', function(){}, $suite);
        $this->spec = $spec;
    });

    it('implements the ReporterInterface', function() {
        $reporter = new ListReporter($this->console);
        expect($reporter instanceof ReporterInterface)->toBeTrue();
    });

    context('beforeSpec', function() {
        it('increments the spec count', function() {
            $reporter = new ListReporter($this->console);

            $countBefore = $reporter->getSpecCount();
            $reporter->beforeSpec($this->spec);
            $countAfter = $reporter->getSpecCount();

            expect($countAfter)->toEqual($countBefore + 1);
        });
    });

    context('afterSpec', function() {
        it('prints the full spec string in grey if it passed', function() {
            $reporter = new ListReporter($this->console);
            $afterSpec = function() use ($reporter) {
                $reporter->afterSpec($this->spec);
            };

            $console = $this->console;
            $title = $this->console->formatter->grey($this->spec);
            expect($afterSpec)->toPrint($title . PHP_EOL);
        });

        it('prints the full spec string in red if it failed', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function() {
                throw new \Exception('test');
            }, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new ListReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->red($this->spec);
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });

        it('prints the full spec string in cyan if incomplete', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new ListReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->cyan($this->spec);
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });

        it('prints the full spec string in yellow if pending', function() {
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', null, $suite);
            $spec->setPending();
            $spec->run();

            $afterSpec = function() use ($spec) {
                $reporter = new ListReporter($this->console);
                $reporter->afterSpec($spec);
            };

            $console = $this->console;
            $specTitle = $console->formatter->yellow($this->spec);
            expect($afterSpec)->toPrint($specTitle . PHP_EOL);
        });
    });
});
