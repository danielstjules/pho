<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

class DotReporter extends AbstractReporter implements ReporterInterface
{
    private static $maxPerLine = 60;

    private $lineLength;

    /**
     * Creates a SpecReporter object, used to render a nested view of test
     * suites and specs.
     *
     * @param Console $console A console for writing output
     */
    public function __construct(Console $console)
    {
        parent::__construct($console);
        $this->lineLength = 0;
    }

    /**
     * Ran prior to test suite execution to print a new line.
     */
    public function beforeRun()
    {
        $this->console->writeLn('');
    }

    /**
     * Ran before an individual spec.
     *
     * @param Spec $spec The spec before which to run this method
     */
    public function beforeSpec(Spec $spec)
    {
        parent::beforeSpec($spec);

        if ($this->lineLength == self::$maxPerLine) {
            $this->console->writeLn('');
            $this->lineLength = 0;
        }
    }

    /**
     * Ran after an individual spec.
     *
     * @param Spec $spec The spec after which to run this method
     */
    public function afterSpec(Spec $spec)
    {
        $this->lineLength += 1;

        if ($spec->isFailed()) {
            $this->failures[] = $spec;
            $failure = $this->formatter->red('F');
            $this->console->write($failure);
        } else if ($spec->isIncomplete()) {
            $this->incompleteSpecs[] = $spec;
            $incomplete = $this->formatter->cyan('I');
            $this->console->write($incomplete);
        } else if ($spec->isPending()) {
            $this->pendingSpecs[] = $spec;
            $pending = $this->formatter->yellow('P');
            $this->console->write($pending);
        } else {
            $this->console->write('.');
        }
    }

    /**
     * Invoked after the test suite has ran, allowing for the display of test
     * results and related statistics.
     */
    public function afterRun()
    {
        $this->console->writeLn('');
        parent::afterRun();
    }

    /**
     * If a given hook failed, adds it to list of failures and prints the
     * result.
     *
     * @param Hook $hook The failed hook
     */
    protected function handleHookFailure(Hook $hook)
    {
        $this->lineLength += 1;
        $this->failures[] = $hook;
        $failure = $this->formatter->red('F');
        $this->console->write($failure);
    }
}
