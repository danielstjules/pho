<?php

namespace pho\Console;

class ConsoleOption
{
    private $shortName;

    private $longName;

    private $description;

    private $argumentName;

    private $value;

    public function __construct($shortName, $longName, $description,
                                $argumentName = null)
    {
        $this->shortName = $shortName;
        $this->longName  = $longName;

        $this->description  = $description;
        $this->argumentName = $argumentName;

        $this->value = false;
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

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function acceptsArguments()
    {
        return ($this->argumentName !== null);
    }
}
