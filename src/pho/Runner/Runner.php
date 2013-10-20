<?php

namespace pho\Runner;

use pho\Suite\Suite;
use pho\Runnable\Runnable;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

class Runner
{
    public static $reporter;

    public static $console;

    private static $suites = [];

    private static $current;

    /**
     * Constructs a test Suite, assigning it the given title and anonymous
     * function. If it's a child of another suite, a reference to the parent
     * suite is stored. This is done by tracking the current and previously
     * defined suites.
     *
     * @param string   $title   A title to be associated with the suite
     * @param callable $context The closure to invoke when the suite is ran
     */
    public static function describe($title, callable $context)
    {
        $previous = self::$current;
        $suite = new Suite($title, $context);
        $suite->parent = $previous;

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

    /**
     * Constructs a new Spec, adding it to the list of specs in the current suite.
     *
     * @param string   $title   A title to be associated with the spec
     * @param callable $context The closure to invoke when the spec is ran
     */
    public static function it($title, callable $context)
    {
        $spec = new Spec($title, $context, self::$current);
        self::$current->specs[] = $spec;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to the parent
     * suite's context.
     *
     * @param callable $context The closure to be ran before the suite
     */
    public static function before(callable $context)
    {
        $before = new Hook($context);
        self::$current->before = $before;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran after the parent
     * suite's context.
     *
     * @param callable $context The closure to be ran after the suite
     */
    public static function after(callable $context)
    {
        $after = new Hook($context);
        self::$current->after = $after;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param callable $context The closure to be ran before each spec
     */
    public static function beforeEach(callable $context)
    {
        $beforeEach = new Hook($context);
        self::$current->beforeEach = $beforeEach;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param callable $context The closure to be ran after each spec
     */
    public static function afterEach(callable $context)
    {
        $afterEach = new Hook($context);
        self::$current->afterEach = $afterEach;
    }

    /**
     * Starts the test runner by first invoking the associated reporter's
     * beforeRun() method, then iterating over all defined suites and running
     * their specs, and calling the reporter's afterRun() when complete.
     */
    public static function run()
    {
        // Parse the command line options, load files
        self::$console->parseArguments();
        self::loadFiles(self::$console->getPaths());

        // Get and instantiate the reporter class
        $reporterClass = self::$console->getReporterClass();
        self::$reporter = new $reporterClass();

        self::$reporter->beforeRun();

        foreach (self::$suites as $suite) {
            self::runSuite($suite);
        }

        self::$reporter->afterRun();
    }

    /**
     * Given a list of valid paths, calls require_once to load files while also
     * recursively loading any files found in directories.
     *
     * @param array $paths An array of strings referring to valid file paths
     */
    public static function loadFiles($paths)
    {
        foreach($paths as $path) {
            if (is_file($path)) {
                require_once($path);
                continue;
            }

            $path = realpath($path);
            $dirIterator = new \RecursiveDirectoryIterator($path);
            $iterator = new \RecursiveIteratorIterator($dirIterator);

            $files = new \RegexIterator($iterator, '/^.+Spec\.php$/i',
                \RecursiveRegexIterator::GET_MATCH);

            foreach ($files as $filename => $file) {
                require_once($filename);
            }
        }
    }

    /**
     * Runs a particular suite by running the associated hooks and reporter,
     * methods, iterating over its child suites and recursively calling itself,
     * followed by running its specs.
     *
     * @param Suite $suite The suite to run
     */
    private static function runSuite(Suite $suite)
    {
        self::runRunnable($suite->before);
        self::$reporter->beforeSuite($suite);

        // Run nested suites
        foreach ($suite->suites as $nestedSuite) {
            self::runRunnable($suite->beforeEach);
            self::runSuite($nestedSuite);
            self::runRunnable($suite->afterEach);
        }

        // Run the specs
        self::runSpecs($suite);

        self::$reporter->afterSuite($suite);
        self::runRunnable($suite->after);
    }

    /**
     * Runs the specs associated with the provided test suite. It iterates
     * over and runs each spec, calling the reporters beforeSpec and afterSpec
     * methods, as well as the suite's beforeEach and aferEach hooks.
     */
    private static function runSpecs(Suite $suite)
    {
        foreach ($suite->specs as $spec) {
            self::runRunnable($suite->beforeEach);
            self::$reporter->beforeSpec($spec);

            self::runRunnable($spec);

            self::$reporter->afterSpec($spec);
            self::runRunnable($suite->afterEach);
        }
    }

    /**
     * A short helper method that calls an object's run() method only if it's
     * an instance of Runnable.
     */
    private static function runRunnable($runnable)
    {
        if ($runnable instanceof Runnable) {
            $runnable->run();
        }
    }
}
