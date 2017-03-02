<?php

namespace pho\Reporter;

use pho\Console\Console;
use pho\Suite\Suite;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

abstract class AbstractReporter
{
    protected $console;

    protected $formatter;

    protected $startTime;

    protected $specCount;

    protected $failures;

    protected $incompleteSpecs;

    protected $pendingSpecs;

    /**
     * Inherited by Reporter classes to generate console output when pho is
     * ran using the command line.
     *
     * @param Console $console A console for writing output
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->formatter = $console->formatter;
        $this->startTime = microtime(true);
        $this->specCount = 0;
        $this->failures = [];
        $this->incompleteSpecs = [];
        $this->pendingSpecs = [];
    }

    /**
     * Ran prior to test suite execution.
     */
    public function beforeRun()
    {
        // Do nothing
    }

    /**
     * Returns the number of specs ran by the reporter.
     *
     * @returns int The number of specs ran
     */
    public function getSpecCount()
    {
        return $this->specCount;
    }

    /**
     * Ran before the containing test suite is invoked.
     *
     * @param Suite $suite The test suite before which to run this method
     */
    public function beforeSuite(Suite $suite)
    {
        $hook = $suite->getHook('before');
        if ($hook && $hook->getException()) {
            $this->handleHookFailure($hook);
        }
    }

    /**
     * Ran after the containing test suite is invoked.
     *
     * @param Suite $suite The test suite after which to run this method
     */
    public function afterSuite(Suite $suite)
    {
        $hook = $suite->getHook('after');
        if ($hook && $hook->getException()) {
            $this->handleHookFailure($hook);
        }
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
     * Invoked after the test suite has ran, allowing for the display of test
     * results and related statistics.
     */
    public function afterRun()
    {
        if (count($this->failures)) {
            $this->console->writeLn("\nFailures:");
        }

        foreach ($this->failures as $spec) {
            $failedText = $this->formatter->red("\n\"$spec\" FAILED");
            $this->console->writeLn($failedText);
            $this->console->writeLn($spec->getException());
        }

        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            $this->console->writeLn("\nFinished in $runningTime seconds");
        }

        $failedCount = count($this->failures);
        $incompleteCount = count($this->incompleteSpecs);
        $pendingCount = count($this->pendingSpecs);
        $specs = ($this->specCount == 1) ? 'spec' : 'specs';
        $failures = ($failedCount == 1) ? 'failure' : 'failures';
        $incomplete = ($incompleteCount) ? ", $incompleteCount incomplete" : '';
        $pending = ($pendingCount) ? ", $pendingCount pending" : '';

        // Print ASCII art if enabled
        if ($this->console->options['ascii']) {
            $this->console->writeLn('');
            $this->drawAscii();
        }

        $summaryText = "\n{$this->specCount} $specs, $failedCount $failures" .
                       $incomplete . $pending;

        // Generate the summary based on whether or not it passed
        if ($failedCount) {
            $summary = $this->formatter->red($summaryText);
        } else {
            $summary = $this->formatter->green($summaryText);
        }

        $summary = $this->formatter->bold($summary);
        $this->console->writeLn($summary);
    }

    /**
     * Prints ASCII art based on whether or not any specs failed. If all specs
     * passed, the randomly selected art is of a happier variety, otherwise
     * there's a lot of anger and flipped tables.
     */
    private function drawAscii()
    {
        $fail = [
            '(╯°□°）╯︵ ┻━┻',
            '¯\_(ツ)_/¯',
            '┻━┻︵ \(°□°)/ ︵ ┻━┻',
            '(ಠ_ಠ)',
            '(ノಠ益ಠ)ノ彡',
            '(✖﹏✖)'
        ];

        $pass = [
            '☜(ﾟヮﾟ☜)',
            '♪ヽ( ⌒o⌒)人(⌒-⌒ )v ♪',
            '┗(^-^)┓',
            'ヽ(^。^)ノ',
            'ヽ(^▽^)v'
        ];

        if (count($this->failures)) {
            $key = array_rand($fail, 1);
            $this->console->writeLn($fail[$key]);
        } else {
            $key = array_rand($pass, 1);
            $this->console->writeLn($pass[$key]);
        }
    }
}
