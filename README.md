![pho](http://danielstjules.com/github/pho-logo.png)

BDD test framework for PHP, inspired by Jasmine and RSpec. Features a familiar
syntax, and a watch command to automatically re-run specs during development.
It can also be extended with custom matchers and reporters.

[![Build Status](https://travis-ci.org/danielstjules/pho.svg?branch=master)](https://travis-ci.org/danielstjules/pho)

 * [Installation](#installation)
 * [Usage](#usage)
 * [Writing Specs](#writing-specs)
 * [Running Specs](#running-specs)
 * [Expectations/Matchers](#expectationsmatchers)
 * [Custom Matchers](#custom-matchers)
 * [Reporters](#reporters)
 * [Mocking](#mocking)
 * [Namespace](#namespace)

## Installation

The following instructions outline installation using Composer. If you don't
have Composer, you can download it from [http://getcomposer.org/](http://getcomposer.org/)
If you're new to composer, make sure to add the vendor bin to your PATH:

``` bash
# Append the following to your profile file, for example in ~/.profile
export PATH=$HOME/.composer/vendor/bin:$PATH
```

To install pho, run:

``` bash
composer global require danielstjules/pho
```

## Usage

``` bash
Usage: pho [options] [files]

Options

   -a   --ascii                     Show ASCII art on completion
   -b   --bootstrap   <bootstrap>   Bootstrap file to load
   -f   --filter      <pattern>     Run specs containing a pattern
   -h   --help                      Output usage information
   -n   --namespace                 Only use namespaced functions
   -r   --reporter    <name>        Specify the reporter to use
   -s   --stop                      Stop on failure
   -v   --version                   Display version number
   -w   --watch                     Watch files for changes and rerun specs
   -C   --no-color                  Disable terminal colors
```

## Writing Specs

Pho exposes a DSL for organizing and writing your tests, which includes the
following functions: `describe`, `context`, `it`, `before`, `after`, `beforeEach`,
`afterEach` and `expect`. Equivalent functions for disabling specs and suites
also exist via `xdescribe`, `xcontext` and `xit`.

To create a suite, `describe` and `context` can be used by passing them a
string and function. Both are interchangeable, though context is more often
nested in a describe to group some set of behaviour. `it` is then used to create
a spec, or test.

A spec may contain multiple expectations or assertions, and will pass so long
as all assertions pass and no exception is uncaught. For asserting values in pho,
`expect` can be used. The function accepts the value to be tested, and may be
chained with a handful of matchers.

``` php
<?php

describe('A suite', function() {
    it('contains specs with expectations', function() {
        expect(true)->toBe(true);
    });

    it('can have specs that fail', function() {
        expect(false)->not()->toBe(false);
    });

    it('can have incomplete specs');
});
```

![intro-screenshot](http://danielstjules.com/github/pho-intro.png)

Objects may be passed between suites and specs with php's `use` keyword. Here's
an example:

``` php
describe('Example', function() {
    $object = new stdClass();
    $object->name = 'pho';

    context('name', function() use ($object) {
        it('is set to pho', function()  use ($object) {
            expect($object->name)->toBe('pho');
        });
    });
});
```

Things can get a bit verbose when dealing with multiple objects that need to be
passed into closures with `use`. To avoid such long lists of arguments, `$this`
can be used to set and retrieve values between suites and specs.

``` php
describe('SomeClass', function() {
    $this->key1 = 'initialValue';
    $this->key2 = 'initialValue';

    context('methodOne()', function() {
        $this->key1 = 'changedValue';

        it('contains a spec', function() {
            expect($this->key1)->toBe('changedValue');
            expect($this->key2)->toBe('initialValue');
        });
    });

    context('methodTwo()', function() {
        it('contains another spec', function() {
            expect($this->key1)->toBe('initialValue');
            expect($this->key2)->toBe('initialValue');
        });
    });
});
```

Hooks are available for running functions as setups and teardowns. `before` is
ran prior to any specs in a suite, and `after`, once all in the suite have been
ran. `beforeEach` and `afterEach` both run their closures once per spec. Note
that `beforeEach` and `afterEach` are both stackable, and will apply to specs
within nested suites. Furthermore, Global hooks may be defined in your bootstrap
file. For example, an afterEach hook in a bootstrap file will run after every
test in your suite.

``` php
describe('Suite with Hooks', function() {
    $this->count = 0;

    beforeEach(function() {
        $this->count = $this->count + 1;
    });

    it('has a count equal to 1', function() {
        expect($this->count)->toEqual(1);
        // A single beforeEach ran
    });

    context('nested suite', function() {
        beforeEach(function() {
            $this->count = $this->count + 1;
        });

        it('has a count equal to 3', function() {
            expect($this->count)->toEqual(3);
            // Both beforeEach closures incremented the value
        });
    });
});
```

## Running Specs

By default, pho looks for specs in either a `test` or `spec` folder under the
working directory. It will recurse through all subfolders and run any files
ending with `Spec.php`, ie: userSpec.php. Furthermore, continuous testing is as
easy as using the `--watch` option, which will monitor all files in the path for
changes, and rerun specs on save.

![watch](http://danielstjules.com/github/pho-watch.gif)

## Expectations/Matchers

#### Type Matching

``` php
expect('pho')->toBeA('string');
expect(1)->notToBeA('string');
expect(1)->not()->toBeA('string');

expect(1)->toBeAn('integer'); // Alias for toBeA
expect('pho')->notToBeAn('integer');
expect('pho')->not()->toBeA('integer');
```

#### Instance Matching

``` php
expect(new User())->toBeAnInstanceOf('User');
expect(new User())->not()->toBeAnInstanceOf('Post');
expect(new User())->notToBeAnInstanceOf('Post');
```

#### Strict Equality Matching

``` php
expect(true)->toBe(true);
expect(true)->not()->toBe(false);
expect(true)->notToBe(false);

expect(['foo'])->toEqual(['foo']); // Alias for toBe
expect(['foo'])->not()->toEqual(true);
expect(['foo'])->notToEqual(true);
```

#### Loose Equality Matching

``` php
expect(1)->toEql(true);
expect(new User('Bob'))->not()->ToEql(new User('Alice'))
expect(new User('Bob'))->notToEql(new User('Alice'))
```

#### Length Matching

``` php
expect(['tdd', 'bdd'])->toHaveLength(2);
expect('pho')->not()->toHaveLength(2);
expect('pho')->notToHaveLength(2);

expect([])->toBeEmpty();
expect('pho')->not()->toBeEmpty();
expect('pho')->notToBeEmpty();
```

#### Inclusion Matching

``` php
expect('Spectacular!')->toContain('Spec');
expect(['a', 'b'])->not()->toContain('c');
expect(['a', 'b'])->notToContain('c');

expect('testing')->toContain('test', 'ing'); // Accepts multiple args
expect(['tdd', 'test'])->not()->toContain('bdd', 'spec');
expect(['tdd', 'test'])->notToContain('bdd', 'spec');

expect(['name' => 'pho'])->toHaveKey('name');
expect(['name' => 'pho'])->not()->toHaveKey('id');
expect(['name' => 'pho'])->notToHaveKey('id');
```

#### Pattern Matching

``` php
expect('tdd')->toMatch('/\w[D]{2}/i');
expect('pho')->not()->toMatch('/\d+/');
expect('pho')->notToMatch('/\d+/');

expect('username')->toStartWith('user');
expect('spec')->not()->toStartWith('test');
expect('spec')->notToStartWith('test');

expect('username')->toEndWith('name');
expect('spec')->not()->toEndWith('s');
expect('spec')->notToEndtWith('s');
```

#### Numeric Matching

``` php
expect(2)->toBeGreaterThan(1);
expect(2)->not()->toBeGreaterThan(2);
expect(1)->notToBeGreaterThan(2);

expect(2)->toBeAbove(1); // Alias for toBeGreaterThan
expect(2)->not()->toBeAbove(2);
expect(1)->notToBeAbove(2);

expect(1)->toBeLessThan(2);
expect(1)->not()->toBeLessThan(1);
expect(2)->notToBeLessThan(1);

expect(1)->toBeBelow(2); // Alias for toBeLessThan
expect(1)->not()->toBeBelow(1);
expect(2)->notToBeBelow(1);

expect(1)->toBeWithin(1, 10); // Inclusive
expect(-2)->not()->toBeWithin(-1, 0);
expect(-2)->notToBeWithin(-1, 0);
```

#### Print Matching

``` php
$callable = function() {
  echo 'test'
};

expect($callable)->toPrint('test');
expect($callable)->not()->toPrint('testing');
expect($callable)->notToPrint('testing');
```

#### Exception Matching

``` php
$callable = function() {
  throw new Custom\Exception('error!');
};

expect($callable)->toThrow('Custom\Exception');
expect($callable)->not()->toThrow('\ErrorException');
expect($callable)->notToThrow('\ErrorException');
```

## Custom Matchers

Custom matchers can be added by creating a class that implements
`pho\Expectation\Matcher\MatcherInterface` and registering the matcher with
`pho\Expectation\Expectation::addMatcher()`. Below is an example of a basic
matcher:

``` php
namespace example;

use pho\Expectation\Matcher\MatcherInterface;

class ExampleMatcher implements MatcherInterface
{
    protected $expectedValue;

    public function __construct($expectedValue)
    {
        $this->expectedValue = $expectedValue;
    }

    public function match($actualValue)
    {
        return ($actualValue === $this->expectedValue);
    }

    public function getFailureMessage($negated = false)
    {
        if (!$negated) {
            return "Expected value to be {$this->expectedValue}";
        } else {
            return "Expected value not to be {$this->expectedValue}";
        }
    }
}
```

Registering it:

``` php
use pho\Expectation\Expectation;

// Register the matcher
Expectation::addMatcher('toHaveValue', '\example\ExampleMatcher');
```

And that's it! You would now have access to the following:

``` php
expect($actual)->toHaveValue($expected);
expect($actual)->not()->toHaveValue($expected);
expect($actual)->notToHaveValue($expected);
```

## Reporters

#### dot (default)

```
$ pho --reporter dot exampleSpec.php

.FI

Failures:

"A suite can have specs that fail" FAILED
/Users/danielstjules/Desktop/exampleSpec.php:9
Expected false not to be false

Finished in 0.00125 seconds

3 specs, 1 failure, 1 incomplete
```

#### spec

```
$ pho --reporter spec exampleSpec.php

A suite
    contains specs with expectations
    can have specs that fail
    can have incomplete specs

Failures:

"A suite can have specs that fail" FAILED
/Users/danielstjules/Desktop/exampleSpec.php:9
Expected false not to be false

Finished in 0.0012 seconds

3 specs, 1 failure, 1 incomplete
```

#### list

```
$ pho --reporter list exampleSpec.php

A suite contains specs with expectations
A suite can have specs that fail
A suite can have incomplete specs

Failures:

"A suite can have specs that fail" FAILED
/Users/danielstjules/Desktop/exampleSpec.php:9
Expected false not to be false

Finished in 0.0012 seconds

3 specs, 1 failure, 1 incomplete
```

## Mocking

Pho doesn't currently provide mocks/stubs out of the box. Instead, it's suggested
that a mocking framework such as [prophecy](https://github.com/phpspec/prophecy)
or [mockery](https://github.com/padraic/mockery) be used.

*Note*: Tests cannot be failed within a test hook. If you need to check
mock object expectations after running a spec, make sure you do so within the
spec body. In the following example this is achieved using the `$teardown`
closure, although the name is not significant.

```php
describe('A suite', function() {
    // Any last checks that could fail a test would go here
    $this->teardown = function() {
        Mockery::close();
    };

    it('should check mock object expectations', function() {
        $mock = Mockery::mock('simplemock');
        $mock->shouldReceive('foo')->with(5)->once()->andReturn(10);
        expect($mock->foo(5))->toBe(10);

        $this->teardown();
    });
});
```

## Namespace

If you'd rather not have pho use the global namespace for its functions, you
can set the `--namespace` flag to force it to only use the pho namespace. This
will be a nicer alternative in PHP 5.6 with
[https://wiki.php.net/rfc/use_function](https://wiki.php.net/rfc/use_function)

``` php
pho\describe('A suite', function() {
    pho\it('contains specs with expectations', function() {
        pho\expect(true)->toBe(true);
    });

    pho\it('can have specs that fail', function() {
        pho\expect(false)->not()->toBe(false);
    });
});
```
