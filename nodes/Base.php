<?php
/**
 * @package axy\config
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\config\nodes;

use axy\config\INode;
use axy\config\errors\ConfigNodeNotExists;
use axy\errors\ContainerReadOnly;

/**
 * The basic class of config nodes
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class Base implements INode
{
    /**
     * Returst a child node
     *
     * @param string $key
     * @return \axy\config\INode
     */
    abstract protected function childGet($key);

    /**
     * Checks if a child node is exist
     *
     * @return boolean
     */
    abstract protected function childExists($key);

    /**
     * Returns a list of available child nodes
     *
     * @return array
     *         the list of names
     */
    abstract protected function childList();

    /**
     * Constructor
     *
     * @param string $path
     * @param \axy\config\IRootNode $root
     */
    public function __construct($path, \axy\config\IRootNode $root = null)
    {
        $this->path = $path;
        $this->root = $root;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function get($path)
    {
        if (!is_array($path)) {
            $path = explode('.', $path);
        }
        if (empty($path)) {
            return $this;
        }
        $key = array_shift($path);
        $child = $this->__get($key);
        if (!empty($path)) {
            if ($child instanceof INode) {
                return $child->get($path);
            }
            $key = ($this->path !== '') ? $this->path.'.'.$key : $key;
            throw new ConfigNodeNotExists($key, 'Config');
        }
        return $child;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($path)
    {
        if (!is_array($path)) {
            $path = explode('.', $path);
        }
        if (empty($path)) {
            return true;
        }
        $key = array_shift($path);
        if (!$this->__isset($key)) {
            return false;
        }
        if (empty($path)) {
            return true;
        }
        $child = $this->__get($key);
        if (!($child instanceof INode)) {
            return false;
        }
        return $child->exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        if ($this->value === null) {
            $this->loadChilds();
            $this->value = [];
            foreach ($this->childs as $key => $child) {
                if ($child instanceof INode) {
                    $child = $child->getValue();
                }
                $this->value[$key] = $child;
            }
        }
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootNode()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->childs)) {
            return $this->childs[$key];
        }
        if ($this->loaded || (!$this->childExists($key))) {
            $key = ($this->path !== '') ? $this->path.'.'.$key : $key;
            throw new ConfigNodeNotExists($key, 'Config');
        }
        $child = $this->childGet($key);
        $this->childs[$key] = $child;
        return $child;
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($key)
    {
        if (array_key_exists($key, $this->childs)) {
            return true;
        }
        if ($this->loaded) {
            return false;
        }
        return $this->childExists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($key, $value)
    {
        throw new ContainerReadOnly('Config', null, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function __unset($key)
    {
        throw new ContainerReadOnly('Config', null, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->list === null) {
            $this->list = $this->childList();
        }
        return count($this->list);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $this->loadChilds();
        return new \ArrayObject($this->childs);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new ContainerReadOnly('Config', null, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new ContainerReadOnly('Config', null, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '['.$this->path.']';
    }

    protected function loadChilds()
    {
        if ($this->loaded) {
            return true;
        }
        if (!$this->list) {
            $this->list = $this->childList();
        }
        foreach ($this->list as $key) {
            $this->childs[$key] = $this->__get($key);
        }
        $this->loaded = true;
    }

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \axy\config\IRootNode
     */
    protected $root;

    /**
     * @var array
     */
    protected $childs = [];

    /**
     * @var array
     */
    protected $list;

    /**
     * @var array
     */
    protected $value;

    /**
     * @var boolean
     */
    protected $loaded = false;
}
