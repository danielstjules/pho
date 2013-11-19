<?php

use pho\Suite\Suite;

describe('Suite', function() {
    $parent = new Suite('Parent', function() {});
    $child = new Suite('Child', function() {});
    $child->parent = $parent;

    context('__toString', function() use ($child, $parent) {
        it('returns the title if no parent exists', function() use ($parent) {
            expect((string) $parent)->toEqual('Parent');
        });

        it('is preceded by the parent title, if set', function() use ($child) {
            expect((string) $child)->toEqual('Parent Child');
        });
    });

    context('set', function() use ($child, $parent) {
        it('sets a key value pair for the given suite', function() use ($parent) {
            $parent->set('key1', 'parentValue');
            expect($parent->get('key1'))->toEqual('parentValue');
        });

        it('does not modify the parent suite', function() use ($parent, $child) {
            $parent->set('key2', 'parentValue');
            $child->set('key2', 'childValue');

            expect($child->get('key2'))->toEqual('childValue');
            expect($parent->get('key2'))->toEqual('parentValue');
        });
    });

    context('get', function() use ($child, $parent) {
        it('returns the stored value', function() use ($parent) {
            expect($parent->get('key1'))->toEqual('parentValue');
        });

        it("if not set, returns the parent's value", function() use ($child) {
            expect($child->get('key1'))->toEqual('parentValue');
        });

        it('returns null if not found in parents', function() use ($child) {
            expect($child->get('randomkey'))->toBeNull();
        });
    });
});
