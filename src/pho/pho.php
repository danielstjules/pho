<?php

require_once('Formatter/FormatterInterface.php');
require_once('Formatter/CLIFormatter.php');

require_once('Runnable.php');
require_once('Suite.php');
require_once('Hook.php');
require_once('Spec.php');

require_once('Runner.php');

pho\Runner::$formatter = new pho\Formatter\CLIFormatter();

// Please forgive, for I have polluted the global namespace

function describe($title, $context)
{
    pho\Runner::describe($title, $context);
}

function it($title, $context)
{
    pho\Runner::it($title, $context);
}

function before($context)
{
    pho\Runner::before($context);
}

function after($context)
{
    pho\Runner::after($context);
}

function beforeEach($context)
{
    pho\Runner::beforeEach($context);
}

function afterEach($context)
{
    pho\Runner::afterEach($context);
}
