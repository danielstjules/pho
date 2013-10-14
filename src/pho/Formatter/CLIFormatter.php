<?php

namespace pho\Formatter;

use pho;

class CLIFormatter implements FormatterInterface
{
    private $startTime;

    private $depth;

    private $totalSpecs;

    private $failedSpecs;

    public function __construct()
    {
        $this->startTime = microtime(true);

        $this->depth = 0;
        $this->totalSpecs = 0;
        $this->failedSpecs = 0;
    }

    public function beforeRun()
    {
        echo "pho by Daniel St. Jules\n\n";
    }

    public function afterRun()
    {
        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            echo "\nFinished in $runningTime seconds\n";
        }

        echo "\n{$this->totalSpecs} specs, {$this->failedSpecs} failures\n";
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
        if (count($spec->errors) || count($spec->exceptions)) {
            $this->failedSpecs += 1;
            echo ' ✖';
        } else {
            echo ' ✓';
        }

        $this->totalSpecs += 1;
        $this->depth -= 1;
        echo "\n";
    }
}
