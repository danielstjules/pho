<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

class SpecReporter extends AbstractReporter implements ReporterInterface
{
    const TAB_SIZE = 4;

    private $depth;

    /**
     * Creates a SpecReporter object, used to render a nested view of test
     * suites and specs.
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

        $leftPad = str_repeat(' ', self::TAB_SIZE * $this->depth);
        $title = $suite->getTitle();
        $this->console->writeLn($leftPad . $title);

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
        $leftPad = str_repeat(' ', self::TAB_SIZE * $this->depth);

        if ($spec->isFailed()) {
            $this->failures[] = $spec;
            $title = $this->formatter->red($spec->getTitle());
        } elseif ($spec->isIncomplete()) {
            $this->incompleteSpecs[] = $spec;
            $title = $this->formatter->cyan($spec->getTitle());
        } elseif ($spec->isPending()) {
            $this->pendingSpecs[] = $spec;
            $title = $this->formatter->yellow($spec->getTitle());
        } else {
            $title = $this->formatter->grey($spec->getTitle());
        }

        $this->console->writeLn($leftPad . $title);
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
        $title = $this->formatter->red($hook->getTitle());
        $leftPad = str_repeat(' ', self::TAB_SIZE * $this->depth);
        $this->console->writeLn($leftPad . $title);
    }
}
