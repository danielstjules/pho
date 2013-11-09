<?php

describe('Some Object', function() {
    before(function() {

    });

    after(function() {

    });

    beforeEach(function() {

    });

    afterEach(function() {

    });

    context('when created', function() {
        beforeEach(function() {

        });

        it('should have a string property', function() {
            $property = 'lol';
            expect('lol')->toBeA('string');
        });

        it('should echo a second number', function() {
            // echo "spec 2\n";
            sleep(1);
        });

        it('should have a property of type other than int', function() {
            $property = 1;
            expect($property)->notToBeAn('integer');
        });

        it('should have a property equal to true', function() {
            $property = false;
            expect($property)->toBeTrue();
        });

        it('should have a property not equal to true', function() {
            $property = "This isn't true!";
            expect($property)->not()->toBeTrue();
        });

        it('should echo a third number', function() {
            throw new Exception('Something went wrong');
        });

        it('should have an empty array property', function() {
            $property = [];
            expect($property)->toBeEmpty();
        });

        it('quick test of toContain with arrays', function() {
            expect(['a'])->toContain('a');
            expect([])->toContain('b');
        });

        it('quick test of toContain with strings', function() {
            expect('testing')->toContain('t');
            expect('testing')->toContain('z');
        });

        it('test toBeAnInstanceOf', function() {
            expect(new stdClass())->toBeAnInstanceOf('stdClass');
            expect(new stdClass())->toBeAnInstanceOf('randomClass');
        });

        context('and user meets some condition', function() {
            it('should do something', function() {
                sleep(1);
            });

            it('should do something else', function() {
                trigger_error('Some error', E_USER_ERROR);
            });
        });

        afterEach(function() {

        });
    });

    it('should be customizable', function() {

    });
});
