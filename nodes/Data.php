<?php
/**
 * @package axy\config\nodes
 */

namespace axy\config\nodes;

use axy\config\IRootNode;

/**
 * The node of array
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Data extends Base
{
    /**
     * The constructor
     *
     * @param array $data
     * @param string $path
     * @param IRootNode $root
     */
    public function __construct(array $data, $path, IRootNode $root = null)
    {
        $this->data = $data;
        parent::__construct($path, $root);
    }

    /**
     * {@inheritdoc}
     */
    protected function childExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function childGet($key)
    {
        $child = $this->data[$key];
        if (is_array($child)) {
            $path = ($this->path !== '') ? $this->path.'.'.$key : $key;
            $child = new self($child, $path, $this->root);
        }
        return $child;
    }

    /**
     * {@inheritdoc}
     */
    protected function childList()
    {
        return array_keys($this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadChilds()
    {
        if ($this->loaded) {
            return true;
        }
        foreach ($this->data as $key => $value) {
            if (!array_key_exists($key, $this->childs)) {
                if (is_array($value)) {
                    $path = ($this->path !== '') ? $this->path.'.'.$key : $key;
                    $value = new self($value, $path, $this->root);
                }
                $this->childs[$key] = $value;
            }
        }
        $this->loaded = true;
        return true;
    }

    /**
     * @var array
     */
    private $data;
}
