<?php

namespace pho\Reporter;

use pho\Suite\Suite;
use pho\Runnable\Spec;

class SpecReporter extends AbstractReporter implements ReporterInterface
{
    private $depth;

    /**
     * Creates a SpecReporter object, used to render a nested view of test
     * suites and specs.
     */
    public function __construct()
    {
        parent::__construct();
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
        echo "$leftPad{$suite->title}\n";

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
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$spec->title}";

        $this->depth += 1;
    }

    /**
     * Ran after an individual spec. May be used to display the results of that
     * particular spec.
     *
     * @param Spec $spec The spec after which to run this method
     */
    public function afterSpec(Spec $spec)
    {
        if (!$spec->passed()) {
            $this->failedSpecs[] = $spec;
            echo ' ✖';
        } else {
            echo ' ✓';
        }

        $this->specCount += 1;
        $this->depth -= 1;
        echo "\n";
    }
}
