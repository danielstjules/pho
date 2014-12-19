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
     * Constructs a test Suite, assigning it the given title and anonymous
     * function. If it's a child of another suite, a reference to the parent
     * suite is stored. This is done by tracking the current and previously
     * defined suites.
     * After all, mark the Suit as pending.
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
     * Constructs a new Spec, adding it to the list of specs in the current suite.
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
     * Constructs a new Spec, adding it to the list of specs in the current suite and mark it as pending.
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
        $before = new Hook($closure, $this->current);
        $this->current->setHook('before', $before);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran after the parent
     * suite's closure.
     *
     * @param \Closure $closure The closure to be ran after the suite
     */
    public function after(\Closure $closure)
    {
        $after = new Hook($closure, $this->current);
        $this->current->setHook('after', $after);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran before each spec
     */
    public function beforeEach(\Closure $closure)
    {
        $beforeEach = new Hook($closure, $this->current);
        $this->current->setHook('beforeEach', $beforeEach);
    }

    /**
     * Constructs a new Hook, defining a closure to be ran prior to each of
     * the parent suite's nested suites and specs.
     *
     * @param \Closure $closure The closure to be ran after each spec
     */
    public function afterEach(\Closure $closure)
    {
        $afterEach = new Hook($closure, $this->current);
        $this->current->setHook('afterEach', $afterEach);
    }

    /**
     * Starts the test runner by first invoking the associated reporter's
     * beforeRun() method, then iterating over all defined suites and running
     * their specs, and calling the reporter's afterRun() when complete.
     */
    public function run()
    {
        $this->bootstrap(self::$console->options['bootstrap']);
        
        // Get and instantiate the reporter class, load files
        $reporterClass = self::$console->getReporterClass();
        $this->reporter = new $reporterClass(self::$console);

        $this->loadFiles(self::$console->getPaths());
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
            $process = proc_open("{$_SERVER['SCRIPT_FILENAME']} {$optionString} {$paths}", $descriptor, $pipes);

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
     * Given a list of valid paths, calls require_once to load files while also
     * recursively loading any files found in directories.
     *
     * @param array $paths An array of strings referring to valid file paths
     */
    public function loadFiles($paths)
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

            $this->runBeforeEachHooks($suite);
            $this->reporter->beforeSpec($spec);

            $this->runRunnable($spec);

            $this->reporter->afterSpec($spec);
            $this->runAfterEachHooks($suite);

            if (self::$console->options['stop'] && $spec->exception) {
                $this->reporter->afterRun();
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
    private function runBeforeEachHooks(Suite $suite)
    {
        if ($suite->getParent()) {
            $this->runBeforeEachHooks($suite->getParent());
        }

        $this->runRunnable($suite->getHook('beforeEach'));
    }

    /**
     * Runs the afterEach hooks of the given suite and its parent suites
     * recursively. They are ran in the order in the opposite order, from inner
     * suites to outer suites.
     *
     * @param Suite $suite The suite with the hooks to run
     */
    private function runAfterEachHooks(Suite $suite)
    {
        $this->runRunnable($suite->getHook('afterEach'));

        if ($suite->getParent()) {
            $this->runAfterEachHooks($suite->getParent());
        }
    }

    /**
     * A short helper method that calls an object's run() method only if it's
     * an instance of Runnable.
     */
    private function runRunnable($runnable)
    {
        if ($runnable instanceof Runnable) {
            $runnable->run();
        }
    }
    
    /**
     * Load bootstrap file
     * 
     * @param $bootstrap
     * @return bool
     */
    private function bootstrap($bootstrap) {

        if($bootstrap === false) {
            return false;
        }

        if(!file_exists($bootstrap)) {
            self::$console->writeLn("File not found: $bootstrap, continuing anyway");
            return false;
        }

        if(!is_readable($bootstrap)) {
            self::$console->writeLn("File not readable: $bootstrap, continuing anyway");
            return false;
        }

        if(! @include_once($bootstrap)) {
            self::$console->writeLn("Unable to include: $bootstrap, continuing anyway");
            return false;
        }

        return true;

    }
}
