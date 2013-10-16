<?php

namespace pho\Reporter;

use pho\Suite;
use pho\Runnable\Spec;
use pho\Exception\Exception;

class CLIReporter implements ReporterInterface
{
    private $startTime;

    private $depth;

    private $specCount;

    private $failedSpecs;

    public function __construct()
    {
        $this->startTime = microtime(true);

        $this->depth = 0;
        $this->specCount = 0;
        $this->failedSpecs = [];
    }

    public function beforeRun()
    {
        echo "pho by Daniel St. Jules\n\n";
    }

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

    public function beforeSuite(Suite $suite)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$suite->title}\n";

        $this->depth += 1;
    }

    public function afterSuite(Suite $suite)
    {
        $this->depth -= 1;
    }

    public function beforeSpec(Spec $spec)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$spec->title}";

        $this->depth += 1;
    }

    public function afterSpec(Spec $spec)
    {
        if ($spec->exception instanceof Exception) {
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
