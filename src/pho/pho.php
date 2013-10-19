<?php

use pho\Runner\Runner;
use pho\Reporter\SpecReporter;
use pho\Reporter\DotReporter;

Runner::$reporter = new SpecReporter();

// Please forgive, for I have polluted the global namespace

/**
 * Calls the runner's describe() method, creating a test suite with the provided
 * closure.
 *
 * @param string   $title   A title associated with this suite
 * @param callable $context The closure associated with the suite, which may
 *                          contain nested suites and specs
 */
function describe($title, callable $context)
{
    Runner::describe($title, $context);
}

/**
 * Calls the runner's it() method, creating a test spec with the provided closure.
 *
 * @param string   $title   A title associated with this spec
 * @param callable $context The closure associated with the spec
 */
function it($title, callable $context)
{
    Runner::it($title, $context);
}

/**
 * Calls the runner's before() method, defining a closure to be ran prior to
 * the parent suite's context.
 *
 * @param callable $context The closure to be ran before the suite
 */
function before(callable $context)
{
    Runner::before($context);
}

/**
 * Calls the runner's after() method, defining a closure to be ran after the
 * parent suite's context.
 *
 * @param callable $context The closure to be ran after the suite
 */
function after(callable $context)
{
    Runner::after($context);
}

/**
 * Calls the runner's beforeEach() method, defining a closure to be ran prior to
 * each of the parent suite's nested suites and specs.
 *
 * @param callable $context The closure to be ran before each spec
 */
function beforeEach(callable $context)
{
    Runner::beforeEach($context);
}

/**
 * Calls the runner's afterEach() method, defining a closure to be ran after
 * each of the parent suite's nested suites and specs.
 *
 * @param callable $context The closure to be ran after the suite
 */
function afterEach(callable $context)
{
    Runner::afterEach($context);
}

// Need to write a command line option parser
require_once($argv[1]);
Runner::run();
