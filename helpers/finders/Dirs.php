<?php
/**
 * @package axy/config
 */

namespace axy\config\helpers\finders;

/**
 * The finder of nested directories
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Dirs extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function createFilename($name)
    {
        return $this->dir.'/'.$name;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkExists($filename)
    {
        return \is_dir($filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadAllItems()
    {
        return \glob($this->dir.'/*', \GLOB_ONLYDIR);
    }
}
