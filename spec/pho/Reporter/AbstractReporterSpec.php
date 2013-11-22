<?php

namespace spec;

use pho\Runnable\Spec;
use pho\Suite\Suite;
use pho\Reporter\AbstractReporter;
use pho\Reporter\ReporterInterface;
use pho\Console\Console;

// Require a mock reporter
class MockReporter extends AbstractReporter implements ReporterInterface
{
    public function beforeSpec(Spec $spec)
    {
        $this->specCount += 1;
    }

    public function afterSpec(Spec $spec)
    {
        return;
    }
}

describe('AbstractReporter', function() {
    $console = new Console([], 'php://output');
    $console->parseArguments();

    $reporter = new MockReporter($console);
    $this->reporter = $reporter;

    context('beforeRun', function() {
        it('prints a newline', function() {
            $this->getParent()->reporter;
            $beforeRun = function() {
                $this->reporter->beforeRun();
            };
            expect($beforeRun)->toPrint(PHP_EOL);
        });
    });

    context('afterRun', function() {
        before(function() {
            // Add a spec and run corresponding reporter hooks
            $reporter = $this->reporter;
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function(){}, $suite);
            $reporter->beforeSpec($spec);
            $reporter->afterSpec($spec);

            ob_start();
            $this->reporter->afterRun();
            $this->printContents = ob_get_contents();
            ob_end_clean();
        });

        it('prints the running time', function() {
            // TODO: Update once pattern matching is added
            $print = $this->printContents;
            expect($print)->toContain('Finished in');
            expect($print)->toContain('seconds');
        });

        it('prints the number of specs and failures', function() {
            expect($this->printContents)->toContain('1 spec, 0 failures');
        });
    });
});
