<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;

abstract class AbstractReporter
{
    protected $console;

    protected $startTime;

    protected $specCount;

    protected $failedSpecs;

    /**
     * Inherited by Reporter classes to generate console output when pho is
     * ran using the command line.
     *
     * @param Console $console A console for writing output
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->startTime = microtime(true);
        $this->specCount = 0;
        $this->failedSpecs = [];
    }

    /**
     * The method is ran prior the test suite execution.
     */
    public function beforeRun()
    {
        $this->console->writeLn("pho by Daniel St. Jules\n");
    }

    /**
     * Invoked after the test suite has ran, allowing for the display of test
     * results and related statistics.
     */
    public function afterRun()
    {
        if (count($this->failedSpecs)) {
            $this->console->writeLn("\nFailures:");
        }

        foreach ($this->failedSpecs as $spec) {
            $this->console->writeLn("\n\"$spec\" FAILED");
            $this->console->writeLn($spec->exception);
        }

        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            $this->console->writeLn("\nFinished in $runningTime seconds");
        }

        $failedCount = count($this->failedSpecs);
        $this->console->writeLn("\n{$this->specCount} specs, $failedCount failures");
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
