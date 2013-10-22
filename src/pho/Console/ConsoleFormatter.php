<?php

namespace pho\Console;

class ConsoleFormatter
{
    private static $foregroundColours = [
        'black' => ['\x1B[30m', '\x1B[39m'],
        'grey'  => ['\x1B[90m', '\x1B[39m'],
        'white' => ['\x1B[37m', '\x1B[39m'],
        'cyan'  => ['\x1B[36m', '\x1B[39m'],
        'green' => ['\x1B[32m', '\x1B[39m'],
        'red'   => ['\x1B[31m', '\x1B[39m'],
    ];

    private static $backgroundColours = [
        'green' => ['\x1B[42m', '\x1B[49m'],
        'red'   => ['\x1B[41m', '\x1B[49m'],
    ];

    private static $styles = [
        'bold'   => ['\x1B[1m', '\x1B[22m'],
        'italic' => ['\x1B[3m', '\x1B[23m'],
    ];

    public function __construct()
    {

    }
}