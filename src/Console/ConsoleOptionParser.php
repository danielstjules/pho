<?php

namespace pho\Console;

class ConsoleOptionParser
{
    private $arguments;

    private $options;

    private $paths;

    private $invalidArguments;

    /**
     * Creates a new ConsoleOptionParser with an empty list of of ConsoleOptions
     * and file paths.
     */
    public function __construct()
    {
        $this->options = [];
        $this->paths = [];
        $this->invalidArguments = [];
    }

    /**
     * Returns the ConsoleOption object either found at the key with the supplied
     * name, or whose short name or long name matches.
     *
     * @param  string        $name The name, shortName, or longName of the option
     * @return ConsoleOption The option matching the supplied name, if it exists
     */
    public function getConsoleOption($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        foreach ($this->options as $option) {
            $longName  = $option->getLongName();
            $shortName = $option->getShortName();

            if ($name == $longName || $name == $shortName) {
                return $option;
            }
        }
    }

    /**
     * Returns an associative array consisting of the names of the ConsoleOptions
     * added to the parser, and their values.
     *
     * @return array The names of the options and their values
     */
    public function getOptions()
    {
        $options = [];
        foreach ($this->options as $name => $option) {
            $options[$name] = $option->getValue();
        }

        return $options;
    }

    /**
     * Adds a new option to be parsed by the ConsoleOptionParser by creating
     * a new ConsoleOption with the supplied longName, shortName, description
     * and optional argumentName. The object is stored in an associative array,
     * using $name as the key.
     *
     * @param string $name         The name of the option, to be used as a key
     * @param string $longName     Long name of the option, including the two
     *                             preceding dashes.
     * @param string $shortName    Short name of the option: a single
     *                             alphabetical character preceded by a dash.
     * @param string $description  Brief description of the option.
     * @param string $argumentName Human readable name for the argument
     */
    public function addOption($name, $longName, $shortName, $description,
                              $argumentName = null)
    {
        $this->options[$name] = new ConsoleOption($longName, $shortName,
            $description, $argumentName);
    }

    /**
     * Returns an array containing the long and short names of all options.
     *
     * @return array The short and long names of the options
     */
    public function getOptionNames()
    {
        $names = [];
        foreach ($this->options as $option) {
            $names[] = $option->getLongName();
            $names[] = $option->getShortName();
        }

        return $names;
    }

    /**
     * Returns an array of strings containing the paths supplied via the command
     * line arguments
     *
     * @return array The paths listed via the arguments
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Returns an array of strings containing the passed invalid arguments.
     *
     * @return array The invalid arguments
     */
    public function getInvalidArguments()
    {
        return $this->invalidArguments;
    }

    /**
     * Parses the supplied arguments, assigning their values to the stored
     * ConsoleOptions. If an option accepts arguments, then any following
     * argument is assigned as its value. Otherwise, the option is merely
     * a flag, and its value is set to true. Any arguments containing a dash as
     * their first character are assumed to be an option, and if invalid,
     * are stored in the $invalidOptions array.
     *
     * @param array An array of strings corresponding to the console arguments
     */
    public function parseArguments($args)
    {
        // Loop over options
        for ($i = 0; $i < count($args); $i++) {
            if (!in_array($args[$i], $this->getOptionNames())) {
                // The option isn't defined
                if (strpos($args[$i], '-') === 0) {
                    $this->invalidArguments[] = $args[$i];
                }

                break;
            }

            // It's a valid option and accepts arguments, add the next argument
            // as its value. Otherwise, just set the option to true
            $option = $this->getConsoleOption($args[$i]);
            if ($option->acceptsArguments()) {
                if (isset($args[$i+1])) {
                    $option->setValue($args[$i + 1]);
                    $i++;
                }
            } else {
                $option->setValue(true);
            }
        }

        // The rest of the arguments are assumed to be paths
        if (!$this->invalidArguments && $i < count($args)) {
            $this->paths = array_slice($args, $i);
        }
    }
}
