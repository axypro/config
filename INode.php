<?php
/**
 * @package axy\config
 */

namespace axy\config;

/**
 * The interface of config tree node
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
interface INode extends \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Returns a path of the current node
     *
     * @return string
     *         the path as "one.two.three" (for a root node - empty string)
     */
    public function getPath();

    /**
     * Returns a node by a nested path
     *
     * @example $node->get('one.two.three')
     * @example $node->get(['one', 'two', 'three'])
     *
     * @param mixed $path
     *        the path (array or string)
     * @return \axy\config\INode
     *         the value (INode or scalar)
     * @throws \axy\config\errors\ConfigNodeNotExists
     *         the specified node is not exists in the config
     */
    public function get($path);

    /**
     * Checks if a node is exist
     *
     * @param mixed $path
     *        the nested path (array or string)
     * @return boolean
     */
    public function exists($path);

    /**
     * Returns the current node content as native value (array or scalar)
     *
     * @return array
     */
    public function getValue();

    /**
     * Returns a root node of the current node tree
     *
     * @return \axy\config\IRootNode
     */
    public function getRootNode();

    /**
     * Returns a child node as an object property
     *
     * @param string $key
     * @return \axy\config\INode
     * @throws \axy\config\errors\ConfigNodeNotExists
     */
    public function __get($key);

    /**
     * Checks if a property (a child node) is exist
     *
     * @param string $key
     * @return \axy\config\INode
     * @throws \axy\config\errors\ConfigNodeNotExists
     */
    public function __isset($key);
}
