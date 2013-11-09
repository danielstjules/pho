<?php

namespace pho\Runner;

use pho\Watcher\Watcher;
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
     * @param \Closure $closure The closure to invoke when the suite is ran
     */
    public static function describe($title, \Closure $closure)
    {
        $previous = self::$current;
        $suite = new Suite($title, $closure);
        $suite->parent = $previous;

        // If current is null, this is the root suite for the file
        if (self::$current === null) {
            self::$suites[] = $suite;
        } else {
            self::$current->suites[] = $suite;
        }

        self::$current = $suite;
        $suite->closure->__invoke();
        self::$current = $previous;
    }

    /**
     * Constructs a new Spec, adding it to the list of specs in the current suite.
     *
     * @param string   $title   A title to be associated with the spec
     * @param \Closure $closure The closure to invoke when the spec is ran
     */
    public static function it($title, \Closure $closure)
    {
        $spec = new Spec($title, $closure, self::$current);
        self::$current->specs[] = $spec;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to the parent
     * suite's closure.
     *
     * @param \Closure $closure The closure to be ran before the suite
     */
    public static function before(\Closure $closure)
    {
        $before = new Hook($closure);
        self::$current->before = $before;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran after the parent
     * suite's closure.
     *
     * @param \Closure $closure The closure to be ran after the suite
     */
    public static function after(\Closure $closure)
    {
        $after = new Hook($closure);
        self::$current->after = $after;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran before each spec
     */
    public static function beforeEach(\Closure $closure)
    {
        $beforeEach = new Hook($closure);
        self::$current->beforeEach = $beforeEach;
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran after each spec
     */
    public static function afterEach(\Closure $closure)
    {
        $afterEach = new Hook($closure);
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

        // Get and instantiate the reporter class
        $reporterClass = self::$console->getReporterClass();
        self::$reporter = new $reporterClass(self::$console);

        self::loadFiles(self::$console->getPaths());
        self::$reporter->beforeRun();

        foreach (self::$suites as $suite) {
            self::runSuite($suite);
        }

        self::$reporter->afterRun();

        if (self::$console->options['watch']) {
            self::watch();
        }
    }

    /**
     * Monitors the the current working directory for modifications, and reruns
     * the specs in another process on change.
     */
    public static function watch()
    {
        $watcher = new Watcher();
        $watcher->watchPath(getcwd());

        $watcher->addListener(function() {
            $paths = implode(' ', self::$console->getPaths());
            $descriptor = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w']
            ];

            // Rebuild option string, without watch
            $optionString = '';
            foreach (self::$console->options as $key => $val) {
                if ($key == 'watch') {
                    continue;
                } elseif ($val === true) { // test
                    $optionString .= "--$key ";
                } elseif ($val) {
                    $optionString .= "--$key $val ";
                }
            }

            // Run pho in another process and echo its stdout
            $process = proc_open("pho $optionString $paths", $descriptor, $pipes);

            if (is_resource($process)) {
                while ($buffer = fread($pipes[1], 16)) {
                    echo $buffer;
                }

                fclose($pipes[0]);
                fclose($pipes[1]);
                proc_close($process);
            }
        });

        // Ever vigilant
        $watcher->watch();
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

        // Run the specs
        self::runSpecs($suite);

        // Run nested suites
        foreach ($suite->suites as $nestedSuite) {
            self::runSuite($nestedSuite);
        }

        self::$reporter->afterSuite($suite);
        self::runRunnable($suite->after);
    }

    /**
     * Runs the specs associated with the provided test suite. It iterates over
     * and runs each spec, calling the reporter's beforeSpec and afterSpec
     * methods, as well as the suite's beforeEach and aferEach hooks. If the
     * filter option is used, only specs containing a pattern are ran. And if
     * the stop flag is used, it quits when an exception or error is thrown.
     *
     * @param Suite $suite The suite containing the specs to run
     */
    private static function runSpecs(Suite $suite)
    {
        foreach ($suite->specs as $spec) {
            // If using the filter option, only run matching specs
            $pattern = self::$console->options['filter'];
            if ($pattern && !preg_match("/$pattern/", $spec)) {
                continue;
            }

            self::runBeforeEachHooks($suite);
            self::$reporter->beforeSpec($spec);

            self::runRunnable($spec);

            self::$reporter->afterSpec($spec);
            self::runAfterEachHooks($suite);

            if (self::$console->options['stop'] && $spec->exception) {
                self::$reporter->afterRun();
                exit(1);
            }
        }
    }

    /**
     * Runs the beforeEach hooks of the given suite and its parent suites
     * recursively. They are ran in the order in which they were defined,
     * from outer suite to inner suites.
     *
     * @param Suite $suite The suite with the hooks to run
     */
    private static function runBeforeEachHooks(Suite $suite)
    {
        if ($suite->parent) {
            self::runBeforeEachHooks($suite->parent);
        }

        self::runRunnable($suite->beforeEach);
    }

    /**
     * Runs the afterEach hooks of the given suite and its parent suites
     * recursively. They are ran in the order in the opposite order, from inner
     * suites to outer suites.
     *
     * @param Suite $suite The suite with the hooks to run
     */
    private static function runAfterEachHooks(Suite $suite)
    {
        self::runRunnable($suite->afterEach);

        if ($suite->parent) {
            self::runAfterEachHooks($suite->parent);
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
