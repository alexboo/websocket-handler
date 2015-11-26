<?php
namespace Alexboo\WebSocketHandler;

class Output
{
    /**
     * Red foreground
     *
     * @const FG_COLOR_RED
     */
    const FG_COLOR_RED = "\e[31m";

    /**
     * Red background
     *
     * @const BG_COLOR_RED
     */
    const BG_COLOR_RED = "\e[41m";

    /**
     * Yellow foreground
     *
     * @const FG_COLOR_YELLOW
     */
    const FG_COLOR_YELLOW = "\e[33m";

    /**
     * Yellow background
     *
     * @const BG_COLOR_YELLOW
     */
    const BG_COLOR_YELLOW = "\e[43m";

    /**
     * Green foreground
     *
     * @const FG_COLOR_GREEN
     */
    const FG_COLOR_GREEN = "\e[32m";

    /**
     * Green background
     *
     * @const BG_COLOR_GREEN
     */
    const BG_COLOR_GREEN = "\e[42m";

    /**
     * Blue foreground
     *
     * @const FG_COLOR_BLUE
     */
    const FG_COLOR_BLUE = "\e[34m";

    /**
     * Blue background
     *
     * @const BG_COLOR_BLUE
     */
    const BG_COLOR_BLUE = "\e[44m";

    /**
     * Default foreground
     *
     * @const FG_COLOR_DEFAULT
     */
    const FG_COLOR_DEFAULT = "\e[0m";

    /**
     * Default background
     *
     * @const BG_COLOR_DEFAULT
     */
    const BG_COLOR_DEFAULT = "\e[49m";

    /**
     * Blink text
     *
     * @const TEXT_BLINK
     */
    const TEXT_BLINK = "\e[5m";

    /**
     * Bold text
     *
     * @const TEXT_BOLD
     */
    const TEXT_BOLD = "\e[1m";

    /**
     * Clear formatting
     *
     * @const RESET_ALL
     */
    const RESET_ALL = "\e[0m";

    /**
     * STDERR
     *
     * @var string
     */
    protected static $error;

    /**
     * STDOUT
     *
     * @var string
     */
    protected static $out;

    /**
     * Get colored text
     *
     * @param $color
     * @param $string
     *
     * @return string
     */
    protected static function getColoredText($color = self::FG_COLOR_DEFAULT, $string)
    {
        return $color . $string . self::FG_COLOR_DEFAULT;
    }

    /**
     * Get colored background
     *
     * @param $color
     * @param $string
     *
     * @return string
     */
    protected static function getColoredBackground($color = self::BG_COLOR_DEFAULT, $string)
    {
        return $color . $string . self::BG_COLOR_DEFAULT;
    }

    /**
     * Input to stderr
     *
     * @param $message string
     */
    public static function error($message)
    {
        fwrite(STDERR, $message . PHP_EOL);
        self::$error .= $message . PHP_EOL;
    }

    /**
     * Input to stdout
     *
     * @param $message string
     */
    public static function out($message)
    {
        fwrite(STDOUT, $message . PHP_EOL);
        self::$out .= $message . PHP_EOL;
    }
    /**
     * Get stdout
     *
     * @return string
     */
    public static function getOut()
    {
        return self::$out;
    }

    /**
     * Get stderr
     *
     * @return string
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * Clear output
     */
    public static function clear()
    {
        unset(self::$error);
        self::$error = '';

        unset(self::$out);
        self::$out = '';
    }
}