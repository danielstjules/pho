<?php

namespace pho\Runner;

use pho\Watcher\Watcher;
use pho\Suite\Suite;
use pho\Runnable\Runnable;
use pho\Runnable\Spec;
use pho\Runnable\Hook;

class Runner
{
    public static $console;

    protected static $instance;

    private $reporter;

    private $suites = [];

    private $current;

    private $root;

    /**
     * Creates a new Runner.
     */
    public function __construct()
    {
        // Add a root suite, useful for defining hooks in a bootstrap
        $this->root = new Suite('', function() {
            // no-op
        }, null);

        $this->current = $this->root;
    }

    /**
     * Returns the singleton instance.
     *
     * @return Runner The singleton instance
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Constructs a test Suite, assigning it the given title and anonymous
     * function. If it's a child of another suite, a reference to the parent
     * suite is stored. This is done by tracking the current and previously
     * defined suites.
     *
     * @param string   $title   A title to be associated with the suite
     * @param \Closure $closure The closure to invoke when the suite is ran
     */
    public function describe($title, \Closure $closure)
    {
        $previous = $this->current;
        $suite = new Suite($title, $closure, $previous);

        if ($this->current === $this->root) {
            $this->suites[] = $suite;
        } else {
            $this->current->addSuite($suite);
        }

        $this->current = $suite;
        $suite->getClosure()->__invoke();
        $this->current = $previous;
    }

    /**
     * Creates a suite and marks it as pending.
     *
     * @param string   $title   A title to be associated with the suite
     * @param \Closure $closure The closure to invoke when the suite is ran
     */
    public function xdescribe($title, \Closure $closure)
    {
        $previous = $this->current;
        $suite = new Suite($title, $closure, $previous);
        $suite->setPending();

        // If current is null, this is the root suite for the file
        if ($this->current === null) {
            $this->suites[] = $suite;
        } else {
            $this->current->addSuite($suite);
        }

        $this->current = $suite;
        $suite->getClosure()->__invoke();
        $this->current = $previous;
    }

    /**
     * Constructs a new Spec, adding it to the list of specs in the current
     * suite.
     *
     * @param string   $title   A title to be associated with the spec
     * @param \Closure $closure The closure to invoke when the spec is ran
     */
    public function it($title, \Closure $closure = null)
    {
        $spec = new Spec($title, $closure, $this->current);
        $this->current->addSpec($spec);
    }

    /**
     * Constructs a new Spec, adding it to the list of specs in the current
     * suite and mark it as pending.
     *
     * @param string   $title   A title to be associated with the spec
     * @param \Closure $closure The closure to invoke when the spec is ran
     */
    public function xit($title, \Closure $closure = null)
    {
        $spec = new Spec($title, $closure, $this->current);
        $spec->setPending();
        $this->current->addSpec($spec);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to the parent
     * suite's closure.
     *
     * @param \Closure $closure The closure to be ran before the suite
     */
    public function before(\Closure $closure)
    {
        $key = 'before';
        $before = new Hook($key, $closure, $this->current);
        $this->current->setHook($key, $before);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran after the parent
     * suite's closure.
     *
     * @param \Closure $closure The closure to be ran after the suite
     */
    public function after(\Closure $closure)
    {
        $key = 'after';
        $after = new Hook($key, $closure, $this->current);
        $this->current->setHook($key, $after);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran before each spec
     */
    public function beforeEach(\Closure $closure)
    {
        $key = 'beforeEach';
        $beforeEach = new Hook($key, $closure, $this->current);
        $this->current->setHook($key, $beforeEach);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran after each spec
     */
    public function afterEach(\Closure $closure)
    {
        $key = 'afterEach';
        $afterEach = new Hook($key, $closure, $this->current);
        $this->current->setHook($key, $afterEach);
    }

    /**
     * Starts the test runner by first invoking the associated reporter's
     * beforeRun() method, then iterating over all defined suites and running
     * their specs, and calling the reporter's afterRun() when complete.
     */
    public function run()
    {
        // Get and instantiate the reporter class, load files
        $reporterClass = self::$console->getReporterClass();
        $this->reporter = new $reporterClass(self::$console);

        $this->reporter->beforeRun();
        foreach ($this->suites as $suite) {
            $this->runSuite($suite);
        }
        $this->reporter->afterRun();

        if (self::$console->options['watch']) {
            $this->watch();
        }
    }

    /**
     * Monitors the the current working directory for modifications, and reruns
     * the specs in another process on change.
     */
    public function watch()
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
            $procStr = "{$_SERVER['SCRIPT_FILENAME']} {$optionString} {$paths}";
            $process = proc_open($procStr, $descriptor, $pipes);

            if (is_resource($process)) {
                while ($buffer = fread($pipes[1], 16)) {
                    self::$console->write($buffer);
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
     * Runs a particular suite by running the associated hooks and reporter,
     * methods, iterating over its child suites and recursively calling itself,
     * followed by running its specs.
     *
     * @param Suite $suite The suite to run
     */
    private function runSuite(Suite $suite)
    {
        $this->runRunnable($suite->getHook('before'));
        $this->reporter->beforeSuite($suite);

        // Run the specs
        $this->runSpecs($suite);

        // Run nested suites
        foreach ($suite->getSuites() as $nestedSuite) {
            $this->runSuite($nestedSuite);
        }

        $this->reporter->afterSuite($suite);
        $this->runRunnable($suite->getHook('after'));
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
    private function runSpecs(Suite $suite)
    {
        foreach ($suite->getSpecs() as $spec) {
            // If using the filter option, only run matching specs
            $pattern = self::$console->options['filter'];
            if ($pattern && !preg_match($pattern, $spec)) {
                continue;
            }

            $this->reporter->beforeSpec($spec);

            $this->runBeforeEachHooks($suite, $spec);
            $this->runRunnable($spec);
            $this->runAfterEachHooks($suite, $spec);

            $this->reporter->afterSpec($spec);
        }
    }

    /**
     * Runs the beforeEach hooks of the given suite and its parent suites
     * recursively. They are ran in the order in which they were defined,
     * from outer suite to inner suites.
     *
     * @param Suite $suite The suite with the hooks to run
     * @param Spec  $spec  The spec to assign any hook failures
     */
    private function runBeforeEachHooks(Suite $suite, Spec $spec)
    {
        if ($suite->getParent()) {
            $this->runBeforeEachHooks($suite->getParent(), $spec);
        }

        $hook = $suite->getHook('beforeEach');
        $this->runRunnable($hook);
        if (!$spec->getException() && $hook) {
            $spec->setException($hook->getException());
        }
    }

    /**
     * Runs the afterEach hooks of the given suite and its parent suites
     * recursively. They are ran in the order in the opposite order, from inner
     * suites to outer suites.
     *
     * @param Suite $suite The suite with the hooks to run
     * @param Spec  $spec  The spec to assign any hook failures
     */
    private function runAfterEachHooks(Suite $suite, Spec $spec)
    {
        $hook = $suite->getHook('afterEach');
        $this->runRunnable($hook);
        if (!$spec->getException() && $hook) {
            $spec->setException($hook->getException());
        }

        if ($suite->getParent()) {
            $this->runAfterEachHooks($suite->getParent(), $spec);
        }
    }

    /**
     * A short helper method that calls an object's run() method only if it's
     * an instance of Runnable.
     */
    private function runRunnable($runnable)
    {
        if (!$runnable) return;

        $runnable->run();
        if ($runnable->getException()) {
            self::$console->setExitStatus(1);
            if (self::$console->options['stop']) {
                $this->reporter->afterRun();
                exit(1);
            }
        }
    }
}
