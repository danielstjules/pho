<?php

namespace pho\Formatter;

use pho, pho\Error;

class CLIFormatter implements FormatterInterface
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
            echo "\n\"$spec\" FAILED\n{$spec->error}\n";
        }

        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            echo "\nFinished in $runningTime seconds\n";
        }

        $failedCount = count($this->failedSpecs);
        echo "\n{$this->specCount} specs, $failedCount failures\n";
    }

    public function beforeSuite(pho\Suite $suite)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$suite->title}\n";

        $this->depth += 1;
    }

    public function afterSuite(pho\Suite $suite)
    {
        $this->depth -= 1;
    }

    public function beforeSpec(pho\Spec $spec)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$spec->title}";

        $this->depth += 1;
    }

    public function afterSpec(pho\Spec $spec)
    {
        if ($spec->error instanceof Error\Error) {
            $this->failedSpecs[] = $spec;
            echo ' ✖';
        } else {
            echo ' ✓';
        }

        $this->totalSpecs += 1;
        $this->depth -= 1;
        echo "\n";
    }
}
