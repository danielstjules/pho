<?php

namespace pho\Console;

class ConsoleFormatter
{
    private static $foregroundColours = [
        'black' => ["\x1b[30m", "\x1b[39m"],
        'grey'  => ["\x1b[90m", "\x1b[39m"],
        'white' => ["\x1b[37m", "\x1b[39m"],
        'cyan'  => ["\x1b[36m", "\x1b[39m"],
        'green' => ["\x1b[32m", "\x1b[39m"],
        'red'   => ["\x1b[31m", "\x1b[39m"],
    ];

    private static $backgroundColours = [
        'greenBg' => ["\x1b[42m", "\x1b[49m"],
        'redBg'   => ["\x1b[41m", "\x1b[49m"],
    ];

    private static $styles = [
        'bold'   => ["\x1b[1m", "\x1b[22m"],
        'italic' => ["\x1b[3m", "\x1b[23m"],
    ];

    private function applyForeground($colour, $text)
    {
        list($startCode, $endCode) = self::$foregroundColours[$colour];
        return $startCode . $text . $endCode;
    }

    private function applyBackground($colour, $text)
    {
        list($startCode, $endCode) = self::$backgroundColours[$colour];
        return $startCode . $text . $endCode;
    }

    private function applyStyle($style, $text)
    {
        list($startCode, $endCode) = self::$styles[$style];
        return $startCode . $text . $endCode;
    }

    public function __call($method, $args)
    {
        if (array_key_exists($method, self::$foregroundColours)) {
            return $this->applyForeground($method, $args[0]);
        } elseif (array_key_exists($method, self::$backgroundColours)) {
            return $this->applyBackground($method, $args[0]);
        } elseif (array_key_exists($method, self::$styles)) {
            return $this->applyStyle($method, $args[0]);
        }

        throw new \Exception("Method {$method} unavailable");
    }
}