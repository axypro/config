<?php
/**
 * @package axy\config
 */

namespace axy\config\helpers;

/**
 * Merger of the two configurations
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Merger
{
    /**
     * Merges of the two configurations
     *
     * @param mixed $base
     *        the parent config
     * @param mixed $ext
     *        the config for extend
     * @return mixed
     *         the result config
     */
    public static function merge($base, $ext)
    {
        if (is_array($base) && is_array($ext)) {
            return array_replace_recursive($base, $ext);
        }
        return $ext;
    }
}
