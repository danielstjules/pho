<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;

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
        $leftPad = str_repeat(' ', self::TAB_SIZE * $this->depth);

        if ($spec->getResult() === Spec::FAILED) {
            $this->failedSpecs[] = $spec;
            $title = $this->formatter->red($spec->getTitle());
        } else if ($spec->getResult() === Spec::INCOMPLETE) {
            $this->incompleteSpecs[] = $spec;
            $title = $this->formatter->cyan($spec->getTitle());
        } else if ($spec->getResult() === Spec::PENDING) {
            $this->pendingSpecs[] = $spec;
            $title = $this->formatter->yellow($spec->getTitle());
        } else {
            $title = $this->formatter->grey($spec->getTitle());
        }

        $this->console->writeLn($leftPad . $title);
    }
}
