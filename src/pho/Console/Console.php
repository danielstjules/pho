<?php

namespace pho\Console;

use pho\Reporter;

class Console
{
    const VERSION = '0.0.1';

    public  $formatter;

    public  $options;

    private $optionParser;

    private $paths;

    private $availableOptions = [
        'ascii'     => ['--ascii',     '-a', 'Show ASCII art on completion'],
        'help'      => ['--help',      '-h', 'Output usage information'],
        'filter'    => ['--filter',    '-f', 'Run specs containing a pattern', 'pattern'],
        'reporter'  => ['--reporter',  '-r', 'Specify the reporter to use', 'name'],
        'stop'      => ['--stop',      '-s', 'Stop on failure'],
        'version'   => ['--version',   '-v', 'Display version number'],
        'watch'     => ['--watch',     '-w', 'Watch files for changes and rerun specs'],

        // TODO: Implement options below
        // 'timeout'   => ['--timeout',   '-t', 'Spec timeout in milliseconds', 'ms'],
        // 'no-color'  => ['--no-color',  '-n', 'Disable terminal colors'],
        // 'generate'  => ['--generate',  '-g', 'Generate suites for classes in path', 'path'],
        // 'random'    => ['--random',    '-R', 'Run specs in a random order'],
        // 'seed'      => ['--seed',      '-S', 'Reproduce random order given seed', 'seed'],
        // 'bootstrap' => ['--bootstrap', '-b', 'Bootstrap file to load', 'bootstrap'],
        // 'processes' => ['--processes', '-p', 'Number of processes to use', 'processes'],
        // 'verbose'   => ['--verbose',   '-V', 'Enable verbose output']
    ];

    private $reporters = ['dot', 'spec'];

    private $defaultDirs = ['test', 'spec'];

    /**
     * The constructor stores the arguments to be parsed, and creates instances
     * of both ConsoleFormatter and ConsoleOptionParser. Also, if either a test
     * or spec directory exists, they are set as the default paths to traverse.
     *
     * @param array $arguments An array of argument strings
     */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;
        $this->options = [];
        $this->paths = [];

        $this->formatter = new ConsoleFormatter();
        $this->optionParser = new ConsoleOptionParser();

        // The default folders to look in are ./test and ./spec
        foreach ($this->defaultDirs as $dir) {
            if (file_exists($dir) && is_dir($dir)) {
                $this->paths[] = $dir;
            }
        }
    }

    /**
     * Returns the namespaced name of the reporter class requested via the
     * command line arguments, defaulting to SpecReporter if not specified.
     *
     * @return string The namespaced class name of the reporter
     */
    public function getReporterClass()
    {
        $reporter = $this->options['reporter'];

        if (!$reporter || !in_array($reporter, $this->reporters)) {
            return 'pho\Reporter\SpecReporter';
        }

        $reporterClass = ucfirst($reporter) . 'Reporter';
        return "pho\\Reporter\\$reporterClass";
    }

    /**
     * Returns an array of strings corresponding to file and directory paths
     * to be traversed.
     *
     * @return array An array of paths
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Parses the arguments originally supplied via the constructor, assigning
     * their values to the option keys in the $options array. If the arguments
     * included the help or version option, the corresponding text is printed
     * and the application exits. Furthermore, if the arguments included a
     * non-valid flag or option, an error is printed and the application
     * terminates.
     */
    public function parseArguments()
    {
        // Add the list of options to the OptionParser
        foreach ($this->availableOptions as $name => $desc) {
            $desc[3] = (isset($desc[3])) ? $desc[3] : null;
            list($longName, $shortName, $description, $argumentName) = $desc;

            $this->optionParser->addOption($name, $longName, $shortName,
                $description, $argumentName);
        }

        // Parse the arguments, assign the options and verify the paths
        $this->optionParser->parseArguments($this->arguments);
        $this->options = $this->optionParser->getOptions();

        $paths = $this->optionParser->getPaths();
        if ($paths) {
            $this->paths = $paths;
            $this->verifyPaths();
        }

        // Render help or version text if necessary, and display errors
        if ($this->options['help']) {
            $this->printHelp();
            exit();
        } elseif ($this->options['version']) {
            $this->printVersion();
            exit();
        } elseif ($this->optionParser->getInvalidArguments()) {
            foreach ($this->optionParser->getInvalidArguments() as $invalidArg) {
                $this->writeLn("$invalidArg is not a valid option");
            }
            exit(1);
        }
    }

    /**
     * Outputs a single line, replacing all occurrences of the newline character
     * in the string with PHP_EOL for cross-platform support.
     *
     * @param string $string The string to print
     */
    public function write($string)
    {
        echo str_replace("\n", PHP_EOL, $string);
    }

    /**
     * Outputs a line, followed by a newline, while replacing all occurrences of
     * '\n' in the string with PHP_EOL for cross-platform support.
     *
     * @param string $string The string to print
     */
    public function writeLn($string)
    {
        $this->write($string);
        echo PHP_EOL;
    }

    /**
     * Verifies that all paths set in $this-Paths exist, and if not, it writes
     * an error and exits.
     */
    private function verifyPaths()
    {
        foreach ($this->paths as $path) {
            if (!file_exists($path)) {
                $this->writeLn("The file or path \"{$path}\" doesn't exist");
                exit(1);
            }
        }
    }

    /**
     * Outputs the help text, as required when the --help/-h flag is used. It's
     * done by iterating over $this->availableOptions.
     */
    private function printHelp()
    {
        $this->writeLn("Usage: pho [options] [files]\n");
        $this->writeLn("Options\n");

        // Loop over availableOptions, building the necessary input for
        // ConsoleFormatter::alignText()
        $options = [];
        foreach ($this->availableOptions as $name => $optionInfo) {
            $row = [$optionInfo[1], $optionInfo[0]];
            $row[] = (isset($optionInfo[3])) ? "<{$optionInfo[3]}>" : '';
            $row[] = $optionInfo[2];

            $options[] = $row;
        }

        $pad = str_repeat(' ', 3);
        foreach ($this->formatter->alignText($options, $pad) as $line) {
            $this->writeLn($pad . $line);
        }
    }

    /**
     * Outputs the version information, as defined in the VERSION constant.
     */
    private function printVersion()
    {
        $this->writeLn('pho version ' . self::VERSION);
    }
}
