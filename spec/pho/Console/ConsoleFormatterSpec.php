<?php

use pho\Console\ConsoleFormatter;

describe('ConsoleFormatter', function() {
    $formatter = new ConsoleFormatter;

    context('alignText', function() use ($formatter) {
        it('pads strings to align columns', function() use ($formatter) {
            $multiArray = [
                ['pho', 'b', 'c'],
                ['a', 'test']
            ];

            $aligned = ['phob   c', 'a  test'];
            expect($formatter->alignText($multiArray))->toEqual($aligned);
        });

        it('can use delimiters between columns', function() use ($formatter) {
            $multiArray = [
                ['pho', 'b', 'c'],
                ['a', 'test']
            ];

            $aligned = [
                'pho | b    | c',
                'a   | test'
            ];

            expect($formatter->alignText($multiArray, ' | '))->toEqual($aligned);
        });
    });

    context('calls to applyForeground', function() use ($formatter) {
        it('can set the color black', function() use ($formatter) {
            $formattedText = $formatter->black('test');
            expect($formattedText)->toEqual("\x1b[30mtest\x1b[39m");
        });

        it('can set the color grey', function() use ($formatter) {
            $formattedText = $formatter->grey('test');
            expect($formattedText)->toEqual("\x1b[90mtest\x1b[39m");
        });

        it('can set the color white', function() use ($formatter) {
            $formattedText = $formatter->white('test');
            expect($formattedText)->toEqual("\x1b[37mtest\x1b[39m");
        });

        it('can set the color cyan', function() use ($formatter) {
            $formattedText = $formatter->cyan('test');
            expect($formattedText)->toEqual("\x1b[36mtest\x1b[39m");
        });

        it('can set the color green', function() use ($formatter) {
            $formattedText = $formatter->green('test');
            expect($formattedText)->toEqual("\x1b[32mtest\x1b[39m");
        });

        it('can set the color red', function() use ($formatter) {
            $formattedText = $formatter->red('test');
            expect($formattedText)->toEqual("\x1b[31mtest\x1b[39m");
        });
    });

    context('calls to applyStyle', function() use ($formatter) {
        it('can set the text bold', function() use ($formatter) {
            $formattedText = $formatter->bold('test');
            expect($formattedText)->toEqual("\x1b[1mtest\x1b[22m");
        });

        it('can set the text italic', function() use ($formatter) {
            $formattedText = $formatter->italic('test');
            expect($formattedText)->toEqual("\x1b[3mtest\x1b[23m");
        });
    });
});
