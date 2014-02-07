<?php
/**
 * @package axy/config
 */

namespace axy\config\helpers\finders;

/**
 * Finds items in a directory
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
abstract class Base
{
    /**
     * Constructor
     *
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Get a file name of a item
     *
     * @param string $name
     *        the item name
     * @return string
     *         the file name or NULL if it is not exist
     */
    public function getFilename($name)
    {
        if (\array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }
        if ($this->list !== null) {
            $this->cache[$name] = null;
        }
        $filename = $this->createFilename($name);
        $result = $this->checkExists($filename) ? $filename : null;
        $this->cache[$name] = $result;
        return $result;
    }

    /**
     * Get a list of all items
     *
     * @return array
     */
    public function getList()
    {
        if ($this->list !== null) {
            return $this->list;
        }
        foreach ($this->loadAllItems() as $filename) {
            $name = \basename($filename, $this->suffix);
            $this->cache[$name] = $filename;
        }
        $this->list = [];
        foreach ($this->cache as $k => $v) {
            if ($v !== null) {
                $this->list[] = $k;
            }
        }
        return $this->list;
    }

    /**
     * @param string $name
     * @return string
     */
    abstract protected function createFilename($name);

    /**
     * @param string $filename
     * @return boolean
     */
    abstract protected function checkExists($filename);

    /**
     * @return array
     */
    abstract protected function loadAllItems();


    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $suffix = '';

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $list;
}
