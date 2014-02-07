<?php
/**
 * @package axy\config
 */

namespace axy\config;

use axy\config\helpers\finders\Dirs;
use axy\config\errors\SettingsInvalidFormat;

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
        if (!isset($settings['dir'])) {
            throw new SettingsInvalidFormat('config settings', 'required dir');
        }
        $this->dir = $settings['dir'];
        $this->finder = new Dirs($this->dir);
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
     * Returns the list of available platforms
     *
     * @return array
     */
    public function getListPlatforms()
    {
        return $this->finder->getList();
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

    /**
     * @var string
     */
    private $dir;

    /**
     * @var \axy\config\helpers\finders\Dirs
     */
    private $finder;
}
