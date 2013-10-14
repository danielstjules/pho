<?php

require_once('Formatter/FormatterInterface.php');
require_once('Formatter/CLIFormatter.php');

require_once('Error/Error.php');
require_once('Error/RunnableError.php');
require_once('Error/RunnableException.php');

require_once('Runnable.php');
require_once('Suite.php');
require_once('Hook.php');
require_once('Spec.php');
require_once('Runner.php');

pho\Runner::$formatter = new pho\Formatter\CLIFormatter();

// Please forgive, for I have polluted the global namespace

function describe($title, callable $context)
{
    pho\Runner::describe($title, $context);
}

function it($title, callable $context)
{
    pho\Runner::it($title, $context);
}

function before(callable $context)
{
    pho\Runner::before($context);
}

function after(callable $context)
{
    pho\Runner::after($context);
}

function beforeEach(callable $context)
{
    pho\Runner::beforeEach($context);
}

function afterEach(callable $context)
{
    pho\Runner::afterEach($context);
}
