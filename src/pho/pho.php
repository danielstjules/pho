<?php

pho\Runner::$reporter = new pho\Reporter\CLIReporter();

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

// Need to write a command line option parser
require_once($argv[1]);
pho\Runner::run();
