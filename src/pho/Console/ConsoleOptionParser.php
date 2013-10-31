<?php

namespace pho\Console;

class ConsoleOptionParser
{
    private $arguments;

    private $options;

    private $paths;

    private $invalidArguments;

    public function __construct()
    {
        $this->options = [];
        $this->paths = [];
        $this->invalidArguments = [];
    }

    public function getOption($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        foreach ($this->options as $option) {
            $shortName = $option->getShortName();
            $longName  = $option->getLongName();

            if ($name == $shortName || $name == $longName) {
                return $option;
            }
        }
    }

    public function getOptions()
    {
        $options = [];
        foreach ($this->options as $name => $option) {
            $options[$name] = $option->getValue();
        }

        return $options;
    }

    public function addOption($name, $shortName, $longName, $description,
                              $argumentName = null)
    {
        $this->options[$name] = new ConsoleOption($shortName, $longName,
            $description, $argumentName);
    }

    public function getOptionNames()
    {
        $names = [];
        foreach ($this->options as $option) {
            $names[] = $option->getShortName();
            $names[] = $option->getLongName();
        }

        return $names;
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getInvalidArguments()
    {
        return $this->invalidArguments;
    }

    public function parseArguments($args)
    {
        // Loop over options
        for ($i = 0; $i < count($args); $i++) {
            if (!in_array($args[$i], $this->getOptionNames())) {
                // The option isn't defined
                if (strpos($args[$i], '-') === 0) {
                    $this->invalidArguments[] = "{$args[$i]} is not a valid option";
                }

                break;
            }

            // It's a valid option and accepts arguments, add the next argument
            // as its value. Otherwise, just set the option to true
            $option = $this->getOption($args[$i]);
            if ($option->acceptsArguments() && $i < count($args)) {
                $option->setValue($args[$i + 1]);
                $i++;
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
