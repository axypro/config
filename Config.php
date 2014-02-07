<?php
/**
 * @package axy\config
 */

namespace axy\config;

/**
 * The config (a container of platforms)
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Config
{
    /**
     * Constructor
     *
     * @param array $settings
     * @throws \axy\config\errors\SettingsInvalidFormat
     */
    public function __construct(array $settings)
    {

    }

    /**
     * Returns a root node of a platform config
     *
     * @param string $name
     * @return \axy\config\IRootNode
     * @throws \axy\config\errors\PlatformNotExists
     */
    public function getConfigForPlatform($name)
    {

    }

    /**
     * Returns the list of available platform
     *
     * @return array
     */
    public function getListPlatfoms()
    {

    }

    /**
     * Checks if a platform is exist
     *
     * @param string $name
     * @return boolean
     */
    public function isPlatformExists($name)
    {

    }
}
