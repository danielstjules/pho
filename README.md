pho
===

BDD test framework for PHP, inspired by Jasmine and RSpec. Work in progress.

```
danielstjules:~/GitHub/pho (master =)
$ php -f example.php
pho by Daniel St. Jules

MyClass
    When my class is created
        Third-level nested suite
            Testing deeply nested ✓
            Should throw an error ✖
        should echo a number ✓
        should echo a second number ✓
        should throw an exception ✖
    Last spec, within the first suite ✓

Finished in 0.00029 seconds

6 specs, 2 failures
```
