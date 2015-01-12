<?php
/**
 * @package axy\config
 */

namespace axy\config;

use axy\config\helpers\finders\Dirs;
use axy\config\nodes\Root;
use axy\config\errors\SettingsInvalidFormat;
use axy\config\errors\PlatformNotExists;

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
        if (isset($settings['defparent'])) {
            $this->defparent = $settings['defparent'];
        }
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
        if (!isset($this->platforms[$name])) {
            $dirname = $this->finder->getFilename($name);
            if ($dirname === null) {
                throw new PlatformNotExists($name, null, $this);
            }
            if ($this->defparent) {
                $parentname = call_user_func($this->defparent, $name);
            } else {
                $parentname = ($name === 'base') ? null : 'base';
            }
            $this->platforms[$name] = new Root($dirname, $name, $this, $parentname);
        }
        return $this->platforms[$name];
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
        return ($this->finder->getFilename($name) !== null);
    }

    /**
     * @var string
     */
    private $dir;

    /**
     * @var \axy\config\helpers\finders\Dirs
     */
    private $finder;

    /**
     * @var array
     */
    private $platforms = [];

    /**
     * @var callable
     */
    private $defparent;
}
