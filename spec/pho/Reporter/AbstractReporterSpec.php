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
    $console = new Console([]);
    $console->parseArguments();

    $reporter = new MockReporter($console);
    $this->set('reporter', $reporter);

    context('beforeRun', function() {
        it('prints a newline', function() {
            $beforeRun = function() {
                $this->get('reporter')->beforeRun();
            };
            expect($beforeRun)->toPrint(PHP_EOL);
        });
    });

    context('afterRun', function() {
        before(function() {
            // Add a spec and run corresponding reporter hooks
            $reporter = $this->get('reporter');
            $suite = new Suite('test', function(){});
            $spec = new Spec('testspec', function(){}, $suite);
            $reporter->beforeSpec($spec);
            $reporter->afterSpec($spec);

            ob_start();
            $this->get('reporter')->afterRun();
            $this->set('printContents', ob_get_contents());
            ob_end_clean();
        });

        it('prints the running time', function() {
            // TODO: Update once pattern matching is added
            $print = $this->get('printContents');
            expect($print)->toContain('Finished in');
            expect($print)->toContain('seconds');
        });

        it('prints the number of specs and failures', function() {
            expect($this->get('printContents'))->toContain('1 spec, 0 failures');
        });
    });
});
