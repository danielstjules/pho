<?php

namespace pho\Console;

use pho\Reporter;

class Console
{
    public $options;

    private $arguments;

    private $optionsInfo = [
        ['--help',     '-h', 'Output usage information'],
        ['--version',  '-v', 'Display version number'],
        ['--reporter', '-r', 'Specify the reporter to use', 'name'],
        ['--filter',   '-f', 'Only run tests matching the pattern', 'pattern'],
        ['--stop',     '-s', 'Stop on failure'],
        ['--watch',    '-w', 'Watch files for changes and rerun tests']
    ];

    private $reporters = ['dot', 'spec'];

    public function __construct($arguments)
    {
        if (in_array('--help', $arguments) || in_array('-h', $arguments)) {
            $this->showHelp();
            exit();
        }

        $this->arguments = $arguments;
        $this->options = [];

        foreach ($this->optionsInfo as $optionInfo) {
            $optionInfo[3] = (isset($optionInfo[3])) ? $optionInfo[3] : null;
            list($longName, $shortName, $description, $argumentName) = $optionInfo;

            $this->options[$longName] = new ConsoleOption($longName, $shortName,
                $description, $argumentName);
            $this->options[$shortName] = $this->options[$longName];
        }
    }

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

    public function parseOptions()
    {
        for ($i = 0; $i < count($this->arguments); $i++) {
            $argument = $this->arguments[$i];
            if (!array_key_exists($argument, $this->options)) {
                require($argument);
                continue;
            }

            $option = $this->options[$argument];
            if ($option->acceptsArguments() && $i < count($this->arguments)) {
                $option->setArgument($this->arguments[$i + 1]);
                $i++;
            } else {
                $option->setArgument(true);
            }
        }
    }

    public function showHelp()
    {
        $this->writeLn("Usage: bin/pho [options] [files]\n");
        $this->writeLn("Options\n");

        foreach ($this->optionsInfo as $option) {
            $line = "{$option[1]}, {$option[0]}";
            if (isset($option[3])) {
                $line .= " <{$option[3]}>";
            }

            $this->writeLn($line);
        }
    }

    public function write($string)
    {
        echo str_replace("\n", PHP_EOL, $string);
    }

    public function writeLn($string)
    {
        $this->write($string);
        echo PHP_EOL;
    }
}
