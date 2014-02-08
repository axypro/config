<?php
/**
 * @package axy\config
 */

namespace axy\config\helpers;

/**
 * Load and merge a config node (in a php-file)
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class LoaderPhp
{
    /**
     * Constructor
     *
     * @param string $filename
     *        a name of the config file
     * @param callable $getparent [optional]
     *        a callback to get the parent value
     */
    public function __construct($filename, $getparent = null)
    {
        $this->filename = $filename;
        $this->getparent = $getparent;
    }

    /**
     * Config should not merge (called from config)
     */
    public function notMerge()
    {
        $this->merge = false;
    }

    /**
     * Returns a parent value
     *
     * @param boolean $notmerge [optional]
     *        config should not merge
     */
    public function getParent($notmerge = true)
    {
        $this->merge = !$notmerge;
        return $this->getparent ? \call_user_func($this->getparent) : null;
    }

    /**
     * Returns a config value from the file
     *
     * @return mixed
     */
    public function load()
    {
        $data = include($this->filename);
        if (\is_array($data) && $this->merge && $this->getparent) {
            $parent = \call_user_func($this->getparent);
            $data = Merger::merge($parent, $data);
        }
        return $data;
    }

    /**
     * @var string
     */
    private $filename;

    /**
     * @var array
     */
    private $getparent;

    /**
     * @var boolean
     */
    private $merge = true;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var boolean
     */
    private $loaded = false;
}
