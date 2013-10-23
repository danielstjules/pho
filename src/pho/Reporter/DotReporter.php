<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
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
     * Ran before an individual spec.
     *
     * @param Spec $spec The spec before which to run this method
     */
    public function beforeSpec(Spec $spec)
    {
        if ($this->lineLength == self::$maxPerLine) {
            $this->console->writeLn('');
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

        if (!$spec->passed()) {
            $this->failedSpecs[] = $spec;
            $failure = $this->formatter->red('F');
            $this->console->write($failure);
        } else {
            $this->console->write('.');
        }
    }
}
