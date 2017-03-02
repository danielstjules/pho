<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

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
        parent::beforeSuite($suite);
    }

    /**
     * Ran after the containing test suite is invoked.
     *
     * @param Suite $suite The test suite after which to run this method
     */
    public function afterSuite(Suite $suite)
    {
        $this->depth -= 1;
        parent::afterSuite($suite);
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
            $this->failures[] = $spec;
            $title = $this->formatter->red($spec);
        } elseif ($spec->isIncomplete()) {
            $this->incompleteSpecs[] = $spec;
            $title = $this->formatter->cyan($spec);
        } elseif ($spec->isPending()) {
            $this->pendingSpecs[] = $spec;
            $title = $this->formatter->yellow($spec);
        } else {
            $title = $this->formatter->grey($spec);
        }

        $this->console->writeLn($title);
    }

    /**
     * If a given hook failed, adds it to list of failures and prints the
     * result.
     *
     * @param Hook $hook The failed hook
     */
    protected function handleHookFailure(Hook $hook)
    {
        $this->failures[] = $hook;
        $title = $this->formatter->red($hook);
        $this->console->writeLn($title);
    }
}
