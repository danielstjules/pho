<?php

namespace pho\Console;

/**
 * Console formatter class
 *
 * @package pho\Console
 * @method void   disableColors()       Disable color output
 * @method string black(string $input)  Set string color for output to black
 * @method string grey(string $input)   Set string color for output to grey
 * @method string red(string $input)    Set string color for output to red
 * @method string green(string $input)  Set string color for output to green
 * @method string cyan(string $input)   Set string color for output to cyan
 * @method string yellow(string $input) Set string color for output to yellow
 * @method string white(string $input)  Set string color for output to white
 * @method string bold(string $input)   Set string style for output to bold
 * @method string italic(string $input) Set string style for output to italic
 */
class ConsoleFormatter
{
    private static $foregroundColors = [
        'black'  => ["\033[30m", "\033[0m"],
        'grey'   => ["\033[90m", "\033[0m"],
        'red'    => ["\033[31m", "\033[0m"],
        'green'  => ["\033[32m", "\033[0m"],
        'cyan'   => ["\033[36m", "\033[0m"],
        'yellow' => ["\033[33m", "\033[0m"],
        'white'  => ["\033[37m", "\033[0m"],
    ];

    private static $styles = [
        'bold'   => ["\x1b[1m", "\x1b[22m"],
        'italic' => ["\x1b[3m", "\x1b[23m"],
    ];

    private $enabled = true;

    /**
     * Disables string formatting using ANSI escape sequences. After being
     * invoked, any calls to a color or style function will result in the plain
     * string being returned.
     */
    public function disableANSI() {
        $this->enabled = false;
    }

    /**
     * Given a multidimensional array, formats the text such that each entry
     * is left aligned with all other entries in the given column. The method
     * also takes an optional delimiter for specifying a sequence of characters
     * to separate each column.
     *
     * @param  array  $array     The multidimensional array to format
     * @param  string $delimiter The delimiter to be used between columns
     * @return array  An array of strings containing the formatted entries
     */
    public function alignText($array, $delimiter = '')
    {
        // Get max column widths
        $widths = [];
        foreach ($array as $row) {
            $lengths = array_map('strlen', $row);

            for ($i = 0; $i < count($lengths); $i++) {
                if (isset($widths[$i])) {
                    $widths[$i] = max($widths[$i], $lengths[$i]);
                } else {
                    $widths[$i] = $lengths[$i];
                }
            }
        }

        // Pad lines columns and return an array
        $output = [];
        foreach($array as $row) {
            $entries = [];
            for ($i = 0; $i < count($row); $i++) {
                $entries[] = str_pad($row[$i], $widths[$i]);
            }

            $output[] = implode($entries, $delimiter);
        }

        return $output;
    }

    /**
     * Sets the text color to one of those defined in $foregroundColors.
     *
     * @param  string $color A color corresponding to one of the keys in the
     *                        $foregroundColors array
     * @param  string $text   The text to be modified
     * @return string The original text surrounded by ANSI escape codes
     */
    private function applyForeground($color, $text)
    {
        list($startCode, $endCode) = self::$foregroundColors[$color];

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
     * Applies the passed text color or style to the string. If disabled,
     * it simply returns the passed string.
     *
     * @param  string $method A color corresponding to one of the keys in the
     *                        $foregroundColors array
     * @param  array  $args   An array with a single element: the text to modify
     * @return string The original text surrounded by ANSI escape codes
     *
     * @throws \Exception If $method doesn't correspond to any of the text
     *                    colors or styles defined in this class
     */
    public function __call($method, $args)
    {
        if (!$this->enabled) {
            return $args[0];
        } elseif (array_key_exists($method, self::$foregroundColors)) {
            return $this->applyForeground($method, $args[0]);
        } elseif (array_key_exists($method, self::$styles)) {
            return $this->applyStyle($method, $args[0]);
        }

        throw new \Exception("Method {$method} unavailable");
    }
}
