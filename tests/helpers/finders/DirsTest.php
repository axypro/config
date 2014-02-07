<?php
/**
 * @package axy/config
 */

namespace axy\config\tests\helpers\finders;

use axy\config\helpers\finders\Dirs;

/**
 * @coversDefaultClass axy\config\helpers\finders\Dirs
 */
class DirsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getFilename
     */
    public function testGetFilename()
    {
        $dir = \realpath(__DIR__.'/../../nstst/finders');
        $finder = new Dirs($dir, 'txt');
        $this->assertSame($dir.'/two', $finder->getFilename('two'));
        $this->assertSame($dir.'/three', $finder->getFilename('three'));
        $this->assertNull($finder->getFilename('one'));
        $this->assertNull($finder->getFilename('four'));
        $this->assertNull($finder->getFilename('five'));
    }

    /**
     * @covers ::getList
     */
    public function testGetList()
    {
        $dir = \realpath(__DIR__.'/../../nstst/finders');
        $finder = new Dirs($dir, 'txt');
        $list = $finder->getList();
        \sort($list);
        $this->assertEquals(['three', 'two'], $list);
        $list2 = $finder->getList();
        \sort($list2);
        $this->assertEquals($list, $list2);
    }
}
