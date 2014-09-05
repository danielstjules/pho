<?php

use pho\Console\ConsoleOptionParser;

describe('ConsoleOptionParser', function() {
    $details = [
        'name'  => 'testName',
        'long'  => 'testLongName',
        'short' => 'testShortName',
        'desc'  => 'testDescription',
        'arg'   => 'testArgumentName'
    ];

    context('addOption', function() use ($details) {
        it('creates a new option object', function() use ($details) {
            $parser = new ConsoleOptionParser();
            $parser->addOption($details['name'], $details['long'],
                $details['short'], $details['desc'], $details['arg']);
            $option = $parser->getConsoleOption($details['name']);

            expect($option->getLongName())->toEqual($details['long']);
            expect($option->getShortName())->toEqual($details['short']);
            expect($option->getDescription())->toEqual($details['desc']);
            expect($option->getArgumentName())->toEqual($details['arg']);
        });
    });

    context('getConsoleOption', function() use ($details) {
        $parser = new ConsoleOptionParser();
        $parser->addOption($details['name'], $details['long'],
            $details['short'], $details['desc'], $details['arg']);

        it('can return based on name', function() use ($parser, $details) {
            $option = $parser->getConsoleOption($details['name']);
            expect($option->getLongName())->toEqual($details['long']);
        });

        it('can return based on longName', function() use ($parser, $details) {
            $option = $parser->getConsoleOption($details['long']);
            expect($option->getLongName())->toEqual($details['long']);
        });

        it('can return based on shortName', function() use ($parser, $details) {
            $option = $parser->getConsoleOption($details['short']);
            expect($option->getShortName())->toEqual($details['short']);
        });
    });

    context('getOptions', function() {
        it('returns the values of all options, as name => value', function() {
            $parser = new ConsoleOptionParser();
            $parser->addOption('testName1', 'long', 'short', 'desc', 'arg');
            $parser->addOption('testName2', 'long2', 'short2', 'desc', 'arg');

            $options = $parser->getOptions();
            expect($options)->toEqual([
                'testName1' => false,
                'testName2' => false
            ]);
        });
    });

    context('parseArguments', function() {
        $addOptions = function($parser) {
            $parser->addOption('watch', '--watch', '-w', 'desc');
            $parser->addOption('ascii', '--ascii', '-a', 'desc');

            $parser->addOption('reporter', '--reporter', '-r', 'desc', 'arg');
            $parser->addOption('filter', '--filter', '-f', 'desc', 'arg');
        };

        it('parses long names', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['--watch', '--ascii']);
            $options = $parser->getOptions();

            expect($options)->toEqual([
                'watch'    => true,
                'ascii'    => true,
                'reporter' => false,
                'filter'   => false
            ]);
        });

        it('parses short names', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['-w']);
            $options = $parser->getOptions();

            expect($options)->toEqual([
                'watch'    => true,
                'ascii'    => false,
                'reporter' => false,
                'filter'   => false
            ]);
        });

        it('parses option arguments', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['-w', '--reporter', 'dot']);
            $options = $parser->getOptions();

            expect($options)->toEqual([
                'watch'    => true,
                'ascii'    => false,
                'reporter' => 'dot',
                'filter'   => false
            ]);
        });

        it('ignores option arguments at final position', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['--reporter']);
            $options = $parser->getOptions();

            expect($options)->toEqual([
                'watch'    => false,
                'ascii'    => false,
                'reporter' => false,
                'filter'   => false
            ]);
        });

        it('stores invalid options', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['-w', '--invalidOpt']);
            expect($parser->getInvalidArguments())->toEqual(['--invalidOpt']);
        });

        it('stores paths', function() use ($addOptions) {
            $parser = new ConsoleOptionParser();
            $addOptions($parser);

            $parser->parseArguments(['-r', 'spec', 'path/']);
            expect($parser->getPaths())->toEqual(['path/']);
        });
    });
});
