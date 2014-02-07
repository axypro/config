<?php
/**
 * @package axy\config
 */

namespace axy\config;

/**
 * The interface of root node of config
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
interface IRootNode extends INode
{
    /**
     * Returns a root node of a parent platform (or NULL if this platform is root)
     *
     * @return \axy\config\IRootNode
     */
    public function getParentPlatform();

    /**
     * Returns a container of platforms
     *
     * @return \axy\config\Config
     */
    public function getConfigContainer();

    /**
     * Returns a name of the current platform
     *
     * @return string
     */
    public function getPlatformName();
}
