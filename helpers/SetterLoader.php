<?php
/**
 * @package axy\config
 */

namespace axy\config\helpers;

use axy\config\Loader;

class SetterLoader extends Loader
{
    /**
     * Push a loader to the stack
     *
     * @param \axy\config\helpers\LoaderPhp $loader
     */
    public static function push($loader)
    {
        if (self::$loader) {
            self::$stack[] = self::$loader;
        }
        self::$loader = $loader;
    }

    /**
     * Pop a loader from the stack
     *
     * @return \axy\config\helpers\LoaderPhp|NULL
     */
    public static function pop()
    {
        $loader = self::$loader;
        if (!empty(self::$stack)) {
            self::$loader = \array_pop(self::$stack);
        }
        return $loader;
    }

    /**
     * @var array
     */
    private static $stack = [];
}
