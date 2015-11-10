<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\tst;

class LoaderMock
{
    public static $log = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function notMerge()
    {
        self::$log[] = $this->name.':notMerge';
    }

    public function getParent($notmerge = true)
    {
        self::$log[] = $this->name.':getParent:'.($notmerge ? 1 : 0);
    }

    private $name;
}
