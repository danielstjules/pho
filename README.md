# pho

BDD test framework for PHP, inspired by Jasmine and RSpec. Work in progress.

## Installation

The following instructions outline installation using Composer. If you don't have Composer, you can download it from [http://getcomposer.org/](http://getcomposer.org/)

 * Run either of the following commands, depending on your environment:

```
$ composer global require danielstjules/pho:dev-master
$ php composer.phar global require danielstjules/pho:dev-master
```

* Edit your `~/.bash_profile` or `~/.profile` and add:

```
export PATH=$HOME/.composer/vendor/bin:$PATH
```

## Example output

```
$ pho --help
Usage: pho [options] [files]

Options

   -h   --help                   Output usage information
   -v   --version                Display version number
   -r   --reporter   <name>      Specify the reporter to use
   -f   --filter     <pattern>   Run specs containing a pattern
   -s   --stop                   Stop on failure
   -w   --watch                  Watch files for changes and rerun specs
```

```
$ pho example.php
pho by Daniel St. Jules

Some Object
    when created
        and user meets some condition
            should do something ✓
            should do something else ✖
        should echo a number ✓
        should echo a second number ✓
        should echo a third number ✖
    should be customizable ✓

Failures:

"Some Object when created and user meets some condition should do something else" FAILED
E_USER_ERROR with message 'Some error' in /Users/danielstjules/GitHub/pho/example.php:46

"Some Object when created should echo a third number" FAILED
Exception with message 'Something went wrong' in /Users/danielstjules/GitHub/pho/example.php:36

Finished in 3.00538 seconds

6 specs, 2 failures
```

```
$ pho --filter 'something$' example.php
pho by Daniel St. Jules

Some Object
    when created
        and user meets some condition
            should do something ✓

Finished in 1.00151 seconds

1 spec, 0 failures
```

```
$ pho --reporter dot example.php
pho by Daniel St. Jules

.F..F.
Failures:

"Some Object when created and user meets some condition should do something else" FAILED
E_USER_ERROR with message 'Some error' in /Users/danielstjules/GitHub/pho/example.php:46

"Some Object when created should echo a third number" FAILED
Exception with message 'Something went wrong' in /Users/danielstjules/GitHub/pho/example.php:36

Finished in 3.00538 seconds

6 specs, 2 failures
```
