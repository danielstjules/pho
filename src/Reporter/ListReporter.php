<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;

class ListReporter extends AbstractReporter implements ReporterInterface
{
    private $depth;

    /**
     * Creates a ListReporter object, used to render a list of specs.
     *
     * @param Console $console A console for writing output
     */
    public function __construct(Console $console)
    {
        parent::__construct($console);
        $this->depth = 0;
    }

    /**
     * Ran before the containing test suite is invoked.
     *
     * @param Suite $suite The test suite before which to run this method
     */
    public function beforeSuite(Suite $suite)
    {
        if ($this->depth == 0) {
            $this->console->writeLn('');
        }

        $this->depth += 1;
    }

    /**
     * Ran after the containing test suite is invoked.
     *
     * @param Suite $suite The test suite after which to run this method
     */
    public function afterSuite(Suite $suite)
    {
        $this->depth -= 1;
    }

    /**
     * Ran before an individual spec.
     *
     * @param Spec $spec The spec before which to run this method
     */
    public function beforeSpec(Spec $spec)
    {
        $this->specCount += 1;
    }

    /**
     * Ran after an individual spec. May be used to display the results of that
     * particular spec.
     *
     * @param Spec $spec The spec after which to run this method
     */
    public function afterSpec(Spec $spec)
    {
        if ($spec->isFailed()) {
            $this->failedSpecs[] = $spec;
            $title = $this->formatter->red($spec);
        } else if ($spec->isIncomplete()) {
            $this->incompleteSpecs[] = $spec;
            $title = $this->formatter->cyan($spec);
        } else if ($spec->isPending()) {
            $this->pendingSpecs[] = $spec;
            $title = $this->formatter->yellow($spec);
        } else {
            $title = $this->formatter->grey($spec);
        }

        $this->console->writeLn($title);
    }
}
