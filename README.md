![pho](http://danielstjules.com/pho/logo.png)

BDD test framework for PHP, inspired by Jasmine and RSpec. Work in progress.
Feature requests and pull requests welcome!

[![Build Status](https://travis-ci.org/danielstjules/pho.png)](https://travis-ci.org/danielstjules/pho)

 * [Installation](#installation)
 * [Writing Specs](#writing-specs)
 * [Running Specs](#running-specs)
 * [Expectations/Matchers](#expectationsmatchers)
 * [Reporters](#reporters)
 * [Namespace](#namespace)
 * [Options](#options)

## Installation

The following instructions outline installation using Composer. If you don't
have Composer, you can download it from [http://getcomposer.org/](http://getcomposer.org/)

 * Run either of the following commands, depending on your environment:

```
$ composer global require danielstjules/pho:dev-master
$ php composer.phar global require danielstjules/pho:dev-master
```

* Edit your `~/.bash_profile` or `~/.profile` and add:

```
export PATH=$HOME/.composer/vendor/bin:$PATH
```

## Writing Specs

Pho exposes 8 functions for organizing and writing your tests: `describe`,
`context`, `it`, `before`, `after`, `beforeEach`, `afterEach` and `expect`.

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
});
```

![intro-screenshot](http://danielstjules.com/pho/intro-noshadow.png)

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
that `beforeEach` and `afterEach` are both stacklable, and will apply to specs
within nested suites.

``` php
describe('Suite with Hooks', function() {
    $this->count = 0;

    beforeEach(function() {
        $count = $this->count;
        $this->count = $count + 1;
    });

    it('has a count equal to 1', function() {
        $count = $this->count;
        expect($count)->toEqual(1);
        // A single beforeEach ran
    });

    context('nested suite', function() {
        beforeEach(function() {
            $count = $this->count;
            $this->count = $count + 1;
        });

        it('has a count equal to 3', function() {
            $count = $this->count;
            expect($count)->toEqual(3);
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

![watch](http://danielstjules.com/pho/watch2.gif)

## Expectations/Matchers

#### Type Matching

``` php
expect('pho')->toBeA('string');
expect(1)->notToBeA('string');
expect(1)->not()->toBeA('string');

expect(1)->toBeAn('integer');
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
expect(1)->toBe(1);
expect(1)->not()->toBe(2);
expect(1)->notToBe(2);

expect(['a'])->toEqual(['a']);
expect(['a'])->not()->toEqual(true);
expect(['a'])->notToEqual(true);

expect(null)->toBeNull();
expect('pho')->not()->toBeNull();
expect('pho')->notToBeNull();

expect(true)->toBeTrue();
expect(1)->not()->toBeTrue();
expect(1)->notToBeTrue();

expect(false)->toBeFalse();
expect(0)->not()->toBeFalse();
expect(0)->notToBeFalse();
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

## Reporters

#### dot (default)

```
$ pho --reporter dot exampleSpec.php

.F

Failures:

"A suite can have specs that fail" FAILED
/Users/danielstjules/Desktop/exampleSpec.php:9
Expected false not to be false

Finished in 0.00103 seconds

2 specs, 1 failure
```

#### spec

```
$ pho --reporter spec exampleSpec.php

A suite
    contains specs with expectations
    can have specs that fail

Failures:

"A suite can have specs that fail" FAILED
/Users/danielstjules/Desktop/exampleSpec.php:9
Expected false not to be false

Finished in 0.00106 seconds

2 specs, 1 failure
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

## Options

```
$ bin/pho --help
Usage: pho [options] [files]

Options

   -a   --ascii                   Show ASCII art on completion
   -h   --help                    Output usage information
   -f   --filter      <pattern>   Run specs containing a pattern
   -r   --reporter    <name>      Specify the reporter to use
   -s   --stop                    Stop on failure
   -v   --version                 Display version number
   -w   --watch                   Watch files for changes and rerun specs
   -n   --namespace               Only use namespaced functions
```
