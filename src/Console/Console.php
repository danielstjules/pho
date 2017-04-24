<?php

namespace pho\Console;

use pho\Reporter;
use ReflectionClass;
use ReflectionException;
use pho\Exception\ReporterNotFoundException;

class Console
{
    const VERSION = '1.2.1';

    const DEFAULT_REPORTER = 'pho\\Reporter\\DotReporter';

    public  $formatter;

    public  $options;

    private $optionParser;

    private $paths;

    private $exitStatus;

    private $availableOptions = [
        'ascii'     => ['--ascii',     '-a', 'Show ASCII art on completion'],
        'bootstrap' => ['--bootstrap', '-b', 'Bootstrap file to load', 'bootstrap'],
        'filter'    => ['--filter',    '-f', 'Run specs containing a pattern', 'pattern'],
        'help'      => ['--help',      '-h', 'Output usage information'],
        'namespace' => ['--namespace', '-n', 'Only use namespaced functions'],
        'reporter'  => ['--reporter',  '-r', 'Specify the reporter to use', 'name'],
        'stop'      => ['--stop',      '-s', 'Stop on failure'],
        'version'   => ['--version',   '-v', 'Display version number'],
        'watch'     => ['--watch',     '-w', 'Watch files for changes and rerun specs'],
        'no-color'  => ['--no-color',  '-C', 'Disable terminal colors'],

        // TODO: Implement options below
        // 'processes' => ['--processes', '-p', 'Number of processes to use', 'processes'],
        // 'verbose'   => ['--verbose',   '-V', 'Enable verbose output']
    ];

    private $defaultDirs = ['test', 'spec'];

    private $stream;

    /**
     * The constructor stores the arguments to be parsed, and creates instances
     * of both ConsoleFormatter and ConsoleOptionParser. Also, if either a test
     * or spec directory exists, they are set as the default paths to traverse.
     *
     * @param array  $arguments An array of argument strings
     * @param string $stream    The I/O stream to use when writing
     */
    public function __construct($arguments, $stream)
    {
        $this->arguments = $arguments;
        $this->options = [];
        $this->paths = [];
        $this->stream = fopen($stream, 'w');

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
     * command line arguments, defaulting to DotReporter if not specified.
     *
     * @return string The namespaced class name of the reporter
     * @throws \pho\Exception\ReporterNotFoundException
     */
    public function getReporterClass()
    {
        $reporter = $this->options['reporter'];

        if ($reporter === false) {
            return self::DEFAULT_REPORTER;
        }

        $reporterClass = ucfirst($reporter) . 'Reporter';
        $reporterClass = "pho\\Reporter\\$reporterClass";

        try {
            $reflection = new ReflectionClass($reporterClass);
        } catch (ReflectionException $exception) {
            throw new ReporterNotFoundException($exception);
        }

        return $reflection->getName();
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
     * Returns the error status that should be used to exit after parsing,
     * otherwise it returns null.
     *
     * @return mixed An integer error status, or null
     */
    public function getExitStatus()
    {
        return $this->exitStatus;
    }

    /**
     * Sets the error code to be returned.
     *
     * @param int $exitStatus An integer return code or exit status
     */
    public function setExitStatus($exitStatus)
    {
        $this->exitStatus = $exitStatus;
    }

    /**
     * Parses the arguments originally supplied via the constructor, assigning
     * their values to the option keys in the $options array. If the arguments
     * included the help or version option, the corresponding text is printed.
     * Furthermore, if the arguments included a non-valid flag or option, or
     * if any of the listed paths were invalid, error message is printed.
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

        // Parse the arguments, assign the options
        $this->optionParser->parseArguments($this->arguments);
        $this->options = $this->optionParser->getOptions();

        // Verify the paths, listing any invalid paths
        $paths = $this->optionParser->getPaths();
        if ($paths) {
            $this->paths = $paths;

            foreach ($this->paths as $path) {
                if (!file_exists($path)) {
                    $this->exitStatus = 1;
                    $this->writeLn("The file or path \"{$path}\" doesn't exist");
                }
            }
        }

        // Render help or version text if necessary, and display errors
        if ($this->options['help']) {
            $this->exitStatus = 0;
            $this->printHelp();
        } elseif ($this->options['version']) {
            $this->exitStatus = 0;
            $this->printVersion();
        } elseif ($this->optionParser->getInvalidArguments()) {
            $this->exitStatus = 1;
            foreach ($this->optionParser->getInvalidArguments() as $invalidArg) {
                $this->writeLn("$invalidArg is not a valid option");
            }
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
        fwrite($this->stream, str_replace("\n", PHP_EOL, $string));
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
        fwrite($this->stream, PHP_EOL);
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
