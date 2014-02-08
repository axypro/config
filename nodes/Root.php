<?php
/**
 * @package axy\config
 */

namespace axy\config\nodes;

use axy\config\Config;
use axy\config\IRootNode;
use axy\config\helpers\finders\Files;
use axy\config\helpers\LoaderPhp;

class Root extends Base implements IRootNode
{
    /**
     * Constructor
     *
     * @param string $dirname
     *        a directory name
     * @param string $name
     *        a platform name
     * @param \axy\config\Config $container
     *        a config container
     * @param callable $parentname [optional]
     *        a name of a parent platform
     */
    public function __construct($dirname, $name, Config $container, $parentname = null)
    {
        $this->name = $name;
        $this->container = $container;
        $this->finder = new Files($dirname, 'php');
        $this->parentname = $parentname;
        parent::__construct('', $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentPlatform()
    {
        if ($this->parentname !== null) {
            $this->parent = $this->container->getConfigForPlatform($this->parentname);
            $this->parentname = null;
        }
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlatformName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    protected function childExists($key)
    {
        if ($this->finder->getFilename($key) !== null) {
            return true;
        }
        $parent = $this->getParentPlatform();
        if (!$parent) {
            return false;
        }
        return $parent->__isset($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function childGet($key)
    {
        $child = $this->childGetData($key);
        if (\is_array($child)) {
            $child = new Data($child, $key, $this);
        }
        return $child;
    }

    /**
     * {@inheritdoc}
     */
    protected function childList()
    {
        $list = $this->finder->getList();
        if ($this->getParentPlatform()) {
            $diff = \array_diff($this->parent->childList(), $list);
            $list = \array_merge($list, $diff);
        }
        return $list;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function childGetData($key)
    {
        $filename = $this->finder->getFilename($key);
        if ($filename !== null) {
            $child = include($filename);
            if (\is_array($child)) {
                $parent = $this->getParentPlatform();
                if ($parent && $parent->childExists($key)) {
                    $parvalue = $parent->childGetData($key);
                    if (\is_array($parvalue)) {
                        $child = \array_replace_recursive($parvalue, $child);
                    }
                }
            }
        } elseif ($this->getParentPlatform()) {
            $child = $this->parent->childGetData($key);
        } else {
            \assert('false', 'a child must exist');
        }
        return $child;
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var \axy\config\Config
     */
    private $container;

    /**
     * @var \axy\config\IRootNode
     */
    private $parent;

    /**
     * @var string
     */
    private $parentname;

    /**
     * @var \axy\config\helpers\finders\Files
     */
    private $finder;
}
