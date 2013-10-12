<?php

namespace pho;

class Runner
{
    public static $formatter;

    public static $suites = [];

    public static $current;

    public static function describe($title, $context)
    {
        $previous = self::$current;
        $suite = new Suite($title, $context);

        // If current is null, this is the root suite for the file
        if (self::$current === null) {
            self::$suites[] = $suite;
        } else {
            self::$current->suites[] = $suite;
        }

        self::$current = $suite;
        $suite->context->__invoke();
        self::$current = $previous;
    }

    public static function it($title, $context)
    {
        $spec = new Spec($title, $context);
        self::$current->specs[] = $spec;
    }

    public static function before($context)
    {
        $before = new Hook($context);
        self::$current->before = $before;
    }

    public static function after($context)
    {
        $after = new Hook($context);
        self::$current->after = $after;
    }

    public static function beforeEach($context)
    {
        $beforeEach = new Hook($context);
        self::$current->beforeEach = $beforeEach;
    }

    public static function afterEach($context)
    {
        $afterEach = new Hook($context);
        self::$current->afterEach = $afterEach;
    }

    public static function run()
    {
        foreach (self::$suites as $suite) {
            self::runSuite($suite);
        }
    }

    private static function runSuite($suite)
    {
        self::runRunnable($suite->before);

        // Run the specs
        foreach ($suite->specs as $spec) {
            self::runRunnable($suite->beforeEach);
            self::runRunnable($spec);
            self::runRunnable($suite->afterEach);
        }

        // Run nested suites
        foreach ($suite->suites as $nestedSuite) {
            self::runRunnable($suite->beforeEach);
            self::runSuite($nestedSuite);
            self::runRunnable($suite->afterEach);
        }

        self::runRunnable($suite->after);
    }

    private static function runRunnable($runnable)
    {
        if ($runnable instanceof Runnable) {
            $runnable->run();
        }
    }
}
