<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Runnable\Spec;

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
        $this->specCount += 1;

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

        if ($spec->getResult() === Spec::FAILED) {
            $this->failedSpecs[] = $spec;
            $failure = $this->formatter->red('F');
            $this->console->write($failure);
        } else if ($spec->getResult() === Spec::INCOMPLETE) {
            $this->incompleteSpecs[] = $spec;
            $incomplete = $this->formatter->cyan('I');
            $this->console->write($incomplete);
        } else if ($spec->getResult() === Spec::PENDING) {
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
}
