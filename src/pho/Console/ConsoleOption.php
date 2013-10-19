<?php

namespace pho\Console;

class ConsoleOption
{
    private $shortName;

    private $longName;

    private $description;

    private $argumentName;

    private $default;

    private $argument;

    public function __construct($shortName, $longName, $description,
                               $argumentName = null)
    {
        $this->shortName = $shortName;
        $this->longName = $longName;
        $this->description = $description;
        $this->argumentName = $argumentName;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function getLongName()
    {
        return $this->longName;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getArgumentName()
    {
        return $this->argumentName;
    }

    public function getArgument()
    {
        return $this->argument;
    }

    public function setArgument($argument)
    {
        if ($this->acceptsArguments()) {
            $this->argument = $argument;
        }
    }

    public function acceptsArguments()
    {
        return ($this->argumentName !== null);
    }

    public function isEnabled()
    {
        return ($this->argument !== null);
    }
}