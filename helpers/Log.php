<?php
/**
 * @package axy\config
 */

namespace axy\config\helpers;

/**
 * The logger for debug
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Log
{
    /**
     * Write to the log
     *
     * @param string $message
     */
    public static function write($message)
    {
        self::$logs[] = $message;
    }

    /**
     * Reset the log
     */
    public static function reset()
    {
        self::$logs = [];
    }

    /**
     * Returns the log
     *
     * @param boolean $reset [optional]
     *        get and reset
     */
    public static function get($reset = true)
    {
        $logs = self::$logs;
        if ($reset) {
            self::$logs = [];
        }
        return $logs;
    }

    /**
     * @var array
     */
    private static $logs = [];
}
