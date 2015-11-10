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
            self::$loader->notMerge();
        }
    }

    /**
     * Returns a parent value
     *
     * @param boolean $notMerge [optional]
     *        a config should not merge
     * @return mixed
     */
    public static function getParent($notMerge = true)
    {
        if (self::$loader) {
            return self::$loader->getParent($notMerge);
        }
        return null;
    }

    /**
     * @var \axy\config\helpers\LoaderPhp
     */
    protected static $loader;
}
