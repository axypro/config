<?php
/**
 * @package axy/config
 */

namespace axy\config\helpers\finders;

use axy\config\helpers\Log;

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
        Log::write('is_dir:'.$filename);
        return is_dir($filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadAllItems()
    {
        $pattern = $this->dir.'/*';
        Log::write('glob:'.$pattern);
        return glob($pattern, GLOB_ONLYDIR);
    }
}
