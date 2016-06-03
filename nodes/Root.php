<?php
/**
 * @package axy\config
 */

namespace axy\config\nodes;

use axy\config\Config;
use axy\config\IRootNode;
use axy\config\helpers\finders\Files;
use axy\config\helpers\LoaderPhp;
use axy\config\helpers\SetterLoader;

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
     * @param callable $parentName [optional]
     *        a name of a parent platform
     * @param \axy\config\IExternal $external [optional]
     */
    public function __construct($dirname, $name, Config $container, $parentName = null, $external = null)
    {
        $this->name = $name;
        $this->container = $container;
        $this->finder = new Files($dirname, 'php');
        $this->parentName = $parentName;
        $this->external = $external;
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
        if ($this->parentName !== null) {
            $this->parent = $this->container->getConfigForPlatform($this->parentName);
            $this->parentName = null;
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
            if ($this->external) {
                return $this->external->isExists($key);
            }
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
        if (is_array($child)) {
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
            /** @noinspection PhpUndefinedMethodInspection */
            $diff = array_diff($this->parent->childList(), $list);
            $list = array_merge($list, $diff);
        }
        return $list;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function childGetData($key)
    {
        if (array_key_exists($key, $this->datas)) {
            return $this->datas[$key];
        }
        $parent = $this->getParentPlatform();
        $filename = $this->finder->getFilename($key);
        if ($filename !== null) {
            if ($parent) {
                $getParent = function () use ($parent, $key) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    if (!$parent->childExists($key)) {
                        return null;
                    }
                    /** @noinspection PhpUndefinedMethodInspection */
                    return $parent->childGetData($key);
                };
            } else {
                $getParent = null;
            }
            $loader = new LoaderPhp($filename, $getParent);
            SetterLoader::push($loader);
            $child = $loader->load();
            if ($this->external) {
                $ex = $this->external->get($key);
                if (is_array($ex) && is_array($child)) {
                    $child = array_replace_recursive($ex, $child);
                }
            }
            SetterLoader::pop();
        } elseif ($parent) {
            /** @noinspection PhpUndefinedMethodInspection */
            $child = $parent->childGetData($key);
        } else {
            $child = $this->getFromExternal($key);
        }
        $this->datas[$key] = $child;
        return $child;
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getFromExternal($key)
    {
        if ($this->external && $this->external->isExists($key)) {
            $child = $this->external->get($key);
        } else {
            assert('false', 'a child must exist');
            $child = null;
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
    private $parentName;

    /**
     * @var \axy\config\helpers\finders\Files
     */
    private $finder;

    /**
     * @var array
     */
    private $datas = [];

    /**
     * @var \axy\config\IExternal
     */
    private $external;
}
