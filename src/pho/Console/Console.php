<?php

namespace pho\Console;

use pho\Reporter;

class Console
{
    const VERSION = '0.0.1';

    public  $formatter;

    private $options;

    private $paths;

    private $arguments;

    private $availableOptions = [
        ['--help',     '-h', 'Output usage information'],
        ['--version',  '-v', 'Display version number'],
        ['--reporter', '-r', 'Specify the reporter to use', 'name'],
        ['--filter',   '-f', 'Only run tests matching the pattern', 'pattern'],
        ['--stop',     '-s', 'Stop on failure'],
        ['--watch',    '-w', 'Watch files for changes and rerun tests']
    ];

    private $reporters = ['dot', 'spec'];

    private $defaultDirs = ['test', 'spec'];

    /**
     * The constructor creates a ConsoleOption object for each option the
     * application exposes as a command line argument. Also, if either a test
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

        // Create a ConsoleOption for each option outlined in $availableOptions
        foreach ($this->availableOptions as $optionInfo) {
            $optionInfo[3] = (isset($optionInfo[3])) ? $optionInfo[3] : null;
            list($longName, $shortName, $description, $argumentName) = $optionInfo;

            $this->options[$longName] = new ConsoleOption($longName, $shortName,
                $description, $argumentName);
            $this->options[$shortName] = $this->options[$longName];
        }

        // The default folders to look in are ./test and ./spec
        foreach ($this->defaultDirs as $dir) {
            if (file_exists($dir) && is_dir($dir)) {
                $this->paths[] = $dir;
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
     * Returns the namespaced name of the reporter class requested via the
     * command line arguments, defaulting to SpecReporter if not specified.
     *
     * @return string The namespaced class name of the reporter
     */
    public function getReporterClass()
    {
        $option = $this->options['--reporter'];
        $reporterName = $option->getArgument();

        if (!$option->isEnabled() || !in_array($reporterName, $this->reporters)) {
            return 'pho\Reporter\SpecReporter';
        }

        $reporter = ucfirst($reporterName) . 'Reporter';
        return "pho\\Reporter\\$reporter";
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
     * values to the ConsoleOptions in the $options array. If the arguments
     * included the --help/-h or --version/-v option, the corresponding text is
     * printed and the application exits. Furthermore, if the arguments included
     * a non-valid flag or option, an error is printed and the application
     * terminates.
     */
    public function parseArguments()
    {
        $args = $this->arguments;
        if (in_array('--help', $args) || in_array('-h', $args)) {
            $this->showHelp();
            exit();
        } else if (in_array('--version', $args) || in_array('-v', $args)) {
            $this->showVersion();
            exit();
        }

        // Loop over options
        for ($i = 0; $i < count($args); $i++) {
            if (!array_key_exists($args[$i], $this->options)) {
                // The option isn't defined
                if (strpos($args[$i], '-') === 0) {
                    $this->writeLn("{$args[$i]} is not a valid option");
                    exit();
                } else {
                    // We assume this is a path
                    break;
                }
            }

            // It's a valid option and accepts arguments, add the next argument
            // as its value. Otherwise, just set the option to true
            $option = $this->options[$args[$i]];
            if ($option->acceptsArguments() && $i < count($args)) {
                $option->setArgument($args[$i + 1]);
                $i++;
            } else {
                $option->setArgument(true);
            }
        }

        // The rest of the arguments are assumed to be paths
        if ($i < count($args)) {
            $this->paths = array_slice($args, $i);
            $this->verifyPaths();
        }
    }

    /**
     * Outputs the help text, as required when the --help/-h flag is used. It's
     * done by iterating over $this->availableOptions.
     */
    private function showHelp()
    {
        $this->writeLn("Usage: bin/pho [options] [files]\n");
        $this->writeLn("Options\n");

        $options = [];
        foreach ($this->availableOptions as $option) {
            $row = [$option[1], $option[0]];
            $row[] = (isset($option[3])) ? "<{$option[3]}>" : '';
            $row[] = $option[2];

            $options[] = $row;
        }

        foreach ($this->formatter->alignText($options, '   ') as $line) {
            $this->writeLn($line);
        }
    }

    /**
     * Outputs the version information, as defined in the VERSION constant.
     */
    private function showVersion()
    {
        $this->writeLn('pho version ' . self::VERSION);
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
                exit();
            }
        }
    }
}
