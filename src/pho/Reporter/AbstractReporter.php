<?php

namespace pho\Reporter;

use pho\Suite\Suite;
use pho\Runnable\Spec;

abstract class AbstractReporter
{
    protected $startTime;

    protected $specCount;

    protected $failedSpecs;

    /**
     * Inherited by Reporter classes to generate console output when pho is
     * ran using the command line.
     */
    public function __construct()
    {
        $this->startTime = microtime(true);

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
        return;
    }

    /**
     * Ran after the containing test suite is invoked.
     *
     * @param Suite $suite The test suite after which to run this method
     */
    public function afterSuite(Suite $suite)
    {
        return;
    }
}
