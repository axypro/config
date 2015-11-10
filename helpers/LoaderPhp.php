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
     * @param callable $getParent [optional]
     *        a callback to get the parent value
     */
    public function __construct($filename, $getParent = null)
    {
        $this->filename = $filename;
        $this->getParent = $getParent;
    }

    /**
     * The config should not merge (called from config)
     */
    public function notMerge()
    {
        $this->merge = false;
    }

    /**
     * Returns a parent value
     *
     * @param boolean $notMerge [optional]
     *        a config should not merge
     * @return mixed
     */
    public function getParent($notMerge = true)
    {
        $this->merge = !$notMerge;
        return $this->getParent ? call_user_func($this->getParent) : null;
    }

    /**
     * Returns a config value from the file
     *
     * @return mixed
     */
    public function load()
    {
        Log::write('include:'.$this->filename);
        /** @noinspection PhpIncludeInspection */
        $data = include($this->filename);
        if (is_array($data) && $this->merge && $this->getParent) {
            $parent = call_user_func($this->getParent);
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
    private $getParent;

    /**
     * @var boolean
     */
    private $merge = true;
}
