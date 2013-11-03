<?php

namespace pho\Console;

class ConsoleOption
{
    private $longName;

    private $shortName;

    private $description;

    private $argumentName;

    private $value;

    /**
     * Creates a new ConsoleOption given a long name (--abcd), short name (-a),
     * description and an optional argument name. Setting the argument name
     * determines whether or not the option accepts arguments. By default, the
     * value of an option is false.
     *
     * @param string $longName     Long name of the option, including the two
     *                             preceding dashes.
     * @param string $shortName    Short name of the option: a single
     *                             alphabetical character preceded by a dash.
     * @param string $description  Brief description of the option.
     * @param string $argumentName Human readable name for the argument
     */
    public function __construct($longName, $shortName, $description,
                                $argumentName = null)
    {
        $this->shortName = $shortName;
        $this->longName  = $longName;

        $this->description  = $description;
        $this->argumentName = $argumentName;

        $this->value = false;
    }

    /**
     * Returns the long name (-abcd) of the option.
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * Returns the short name (-a) of the option.
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Returns the description of the option.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the argument name of the option.
     */
    public function getArgumentName()
    {
        return $this->argumentName;
    }

    /**
     * Returns the value of the option.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the option. If the option accepts arguments, the
     * supplied value can be of any type. Otherwise, the value is cast as a
     * boolean.
     *
     * @param mixed $value The value to assign to the option
     */
    public function setValue($value)
    {
        if ($this->acceptsArguments()) {
            $this->value = $value;
        } else {
            $this->value = (boolean) $value;
        }
    }

    /**
     * Returns true if the ConsoleOption accepts arguments, as indicated
     * by the presence of an argument name, and false otherwise.
     *
     * @return boolean Whether or no the option accepts arguments
     */
    public function acceptsArguments()
    {
        return ($this->argumentName !== null);
    }
}
