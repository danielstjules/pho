<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;

class SpecReporter extends AbstractReporter implements ReporterInterface
{
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
        $leftPad = str_repeat('    ', $this->depth);
        $this->console->writeLn($leftPad . $suite->getTitle());

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
        $leftPad = str_repeat('    ', $this->depth);

        if (!$spec->passed()) {
            $this->failedSpecs[] = $spec;
            $title = $this->formatter->red($spec->getTitle());
        } else {
            $title = $this->formatter->grey($spec->getTitle());
        }

        $this->console->writeLn($leftPad . $title);
    }
}
