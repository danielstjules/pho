<?php

namespace pho\Reporter;

use pho\Suite\Suite;
use pho\Runnable\Spec;

class CLIReporter implements ReporterInterface
{
    private $startTime;

    private $depth;

    private $specCount;

    private $failedSpecs;

    /**
     * Creates the CLIReporter object, used in generating console output when
     * pho is ran using the command line.
     */
    public function __construct()
    {
        $this->startTime = microtime(true);

        $this->depth = 0;
        $this->specCount = 0;
        $this->failedSpecs = [];
    }

    /**
     * The method is ran prior the test suite execution.
     */
    public function beforeRun()
    {
        echo "pho by Daniel St. Jules\n\n";
    }

    /**
     * Invoked after the test suite has ran, allowing for the display of test
     * results and related statistics.
     */
    public function afterRun()
    {
        if (count($this->failedSpecs)) {
            echo "\nFailures:\n";
        }

        foreach ($this->failedSpecs as $spec) {
            echo "\n\"$spec\" FAILED\n{$spec->exception}\n";
        }

        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            echo "\nFinished in $runningTime seconds\n";
        }

        $failedCount = count($this->failedSpecs);
        echo "\n{$this->specCount} specs, $failedCount failures\n";
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
