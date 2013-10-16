<?php

use pho\Runner;
use pho\Reporter\CLIReporter;

Runner::$reporter = new CLIReporter();

// Please forgive, for I have polluted the global namespace

function describe($title, callable $context)
{
    Runner::describe($title, $context);
}

function it($title, callable $context)
{
    Runner::it($title, $context);
}

function before(callable $context)
{
    Runner::before($context);
}

function after(callable $context)
{
    Runner::after($context);
}

function beforeEach(callable $context)
{
    Runner::beforeEach($context);
}

function afterEach(callable $context)
{
    Runner::afterEach($context);
}

// Need to write a command line option parser
require_once($argv[1]);
Runner::run();
