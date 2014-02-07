<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\nstst;

class Node extends \axy\config\nodes\Base
{
    public function __construct($path = '', $level = 0)
    {
        $this->path = $path;
        $this->level = $level;
    }

    protected function childGet($key)
    {
        switch ($key) {
            case 'one':
                if ($this->level < 2) {
                    $path = $this->path ? $this->path.'.'.$key : $key;
                    return new Node($path, $this->level + 1);
                }
                return 1;
            case 'two':
                return 2;
            case 'three':
                return 3;
        }
    }

    protected function childExists($key)
    {
        return \in_array($key, ['one', 'two', 'three']);
    }

    protected function childList()
    {
        return ['one', 'two', 'three'];
    }

    private $level;
}
