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

    private static $styles = [
        'bold'   => ["\x1b[1m", "\x1b[22m"],
        'italic' => ["\x1b[3m", "\x1b[23m"],
    ];

    /**
     * Sets the text colour to one of those defined in $foregroundColours.
     *
     * @param  string $colour A colour corresponding to one of the keys in the
     *                        $foregroundColours array
     * @param  string $text   The text to be modified
     * @return string The original text surrounded by ANSI escape codes
     */
    private function applyForeground($colour, $text)
    {
        list($startCode, $endCode) = self::$foregroundColours[$colour];
        return $startCode . $text . $endCode;
    }

    /**
     * Sets the text style to one of those defined in $styles.
     *
     * @param  string $style A style corresponding to one of the keys in the
     *                       $styles array
     * @param  string $text  The text to be modified
     * @return string The original text surrounded by ANSI escape codes
     */
    private function applyStyle($style, $text)
    {
        list($startCode, $endCode) = self::$styles[$style];
        return $startCode . $text . $endCode;
    }

    /**
     * Applies the passed text colour or style to the string.
     *
     * @param  string $method A colour corresponding to one of the keys in the
     *                        $foregroundColours array
     * @param  array  $args   An array with a single element: the text to modify
     * @return string The original text surrounded by ANSI escape codes
     *
     * @throws \Exception If $method doesn't correspond to any of the text
     *                    colours or styles defined in this class
     */
    public function __call($method, $args)
    {
        if (array_key_exists($method, self::$foregroundColours)) {
            return $this->applyForeground($method, $args[0]);
        } elseif (array_key_exists($method, self::$styles)) {
            return $this->applyStyle($method, $args[0]);
        }

        throw new \Exception("Method {$method} unavailable");
    }
}