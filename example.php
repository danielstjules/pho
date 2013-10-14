<?php

require('src/pho/pho.php');

describe('Some Object', function() {
    before(function() {
        // echo "Before\n";
    });

    after(function() {
        // echo "After\n";
    });

    beforeEach(function() {
        // echo "Top BeforeEach\n";
    });

    afterEach(function() {
        // echo "Top AfterEach\n";
    });

    describe('when created', function() {
        beforeEach(function() {
            // echo "beforeEach\n";
        });

        it('should echo a number', function() {
            // echo "spec 1\n";
        });

        it('should echo a second number', function() {
            // echo "spec 2\n";
        });

        it('should echo a third number', function() {
            throw new Exception('Something went wrong');
        });

        describe('and user meets some condition', function() {
            it('should do something', function() {
                // echo "deeply nested";
            });

            it('should do something else', function() {
                trigger_error('Some error', E_USER_ERROR);
            });
        });

        afterEach(function() {
            // echo "afterEach\n";
        });
    });

    it('should be customizable', function() {
        // echo "Last spec\n";
    });
});

pho\Runner::run();
