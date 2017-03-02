<?php

use pho\Suite\Suite;

describe('Suite', function() {
    $root = new Suite('Root', function() {});
    $parent = new Suite('Parent', function() {}, $root);
    $child = new Suite('Child', function() {}, $parent);

    context('__toString', function() use ($child, $parent) {
        it('returns the title if no parent exists', function() use ($parent) {
            expect((string) $parent)->toEqual('Parent');
        });

        it('is preceded by the parent title, if set', function() use ($child) {
            expect((string) $child)->toEqual('Parent Child');
        });
    });

    context('__set', function() use ($child, $parent) {
        it('sets a key value pair for the given suite', function() use ($parent) {
            $parent->key1 = 'parentValue';
            expect($parent->key1)->toEqual('parentValue');
        });

        it('does not modify the parent suite', function() use ($parent, $child) {
            $parent->key2 = 'parentValue';
            $child->key2 = 'childValue';

            expect($child->key2)->toEqual('childValue');
            expect($parent->key2)->toEqual('parentValue');
        });

        it('throws an exception if it conflicts with a method', function() use ($child) {
            $overwriteAttempt = function() {
                $this->addSpec = 'should fail';
            };

            expect($overwriteAttempt)->toThrow('\Exception');
        });
    });

    context('__get', function() use ($child, $parent) {
        it('returns the stored value', function() use ($parent) {
            expect($parent->key1)->toEqual('parentValue');
        });

        it("if not set, returns the parent's value", function() use ($child) {
            expect($child->key1)->toEqual('parentValue');
        });

        it('returns null if not found in parents', function() use ($child) {
            expect($child->randomkey)->toBe(null);
        });
    });

    context('__call', function() use ($child, $parent) {
        $parent->callable1 = function() {
            return 'Callable 1';
        };

        $child->callable2 = function() {
            return 'Callable 2';
        };

        $child->callableWithArgs = function($arg1, $arg2) {
            return "$arg1 $arg2";
        };

        it('invokes the stored callable', function() use ($child) {
            expect($child->callable2())->toBe('Callable 2');
        });

        it('invokes the stored callable with arguments', function() use ($child) {
            $invokeCallable = function() use ($child) {
                return $child->callableWithArgs('test', 'callable');
            };

            expect($invokeCallable())->toBe('test callable');
        });

        it("if not set, invokes the parent's callable", function() use ($child) {
            expect($child->callable1())->toBe('Callable 1');
        });

        it('throws an exception if not found in the parent', function() use ($child) {
            $callInvalidKey = function() use ($child) {
                $child->invalidKey();
            };

            expect($callInvalidKey)->toThrow('\BadFunctionCallException');
        });
    });
});
