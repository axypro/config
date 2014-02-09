<?php
/**
 * @package axy\config
 */

namespace axy\config;

/**
 * The container of static methods for a context of config files
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Loader
{
    /**
     * The config should not merge (called from config)
     */
    public static function notMerge()
    {
        if (self::$loader) {
            return self::$loader->notMerge();
        }
        return null;
    }

    /**
     * Returns a parent value
     *
     * @param boolean $notmerge [optional]
     *        a config should not merge
     */
    public static function getParent($notmerge = true)
    {
        if (self::$loader) {
            return self::$loader->getParent($notmerge);
        }
        return null;
    }

    /**
     * @var \axy\config\helpers\LoaderPhp
     */
    protected static $loader;
}
