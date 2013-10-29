pho
===

BDD test framework for PHP, inspired by Jasmine and RSpec. Work in progress.

Todo:

 * Namespaced option
 * Additional reporters
 * Matchers
 * Diff on expectation failure

```
danielstjules:~/GitHub/pho (master =)
$ bin/pho example.php
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
danielstjules:~/GitHub/pho (master =)
$ bin/pho --reporter dot example.php
pho by Daniel St. Jules

.F..F.
Failures:

"Some Object when created and user meets some condition should do something else" FAILED
E_USER_ERROR with message 'Some error' in /Users/danielstjules/GitHub/pho/example.php:46

"Some Object when created should echo a third number" FAILED
Exception with message 'Something went wrong' in /Users/danielstjules/GitHub/pho/example.php:36

Finished in 3.00503 seconds

0 specs, 2 failures
```
