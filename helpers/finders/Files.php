<?php
/**
 * @package axy/config
 */

namespace axy\config\helpers\finders;

use axy\config\helpers\Log;

/**
 * The finder of nested files
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Files extends Base
{
    /**
     * Constructor
     *
     * @param string $dir
     * @param string $ext [optional]
     */
    public function __construct($dir, $ext = null)
    {
        if (($ext !== '') && ($ext !== null)) {
            $this->suffix = '.'.$ext;
        }
        parent::__construct($dir);
    }

    /**
     * {@inheritdoc}
     */
    protected function createFilename($name)
    {
        return $this->dir.'/'.$name.$this->suffix;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkExists($filename)
    {
        Log::write('is_file:'.$filename);
        return is_file($filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadAllItems()
    {
        $pattern = $this->dir.'/*'.$this->suffix;
        Log::write('glob:'.$pattern);
        $result = glob($pattern);
        if ($this->suffix === '') {
            $copy = $result;
            $result = [];
            foreach ($copy as $item) {
                if (is_file($item) && (strpos(basename($item), '.') === false)) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }
}
