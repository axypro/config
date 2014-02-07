<?php
/**
 * @package axy/config
 */

namespace axy\config\tests\helpers\finders;

use axy\config\helpers\finders\Files;

/**
 * @coversDefaultClass axy\config\helpers\finders\Files
 */
class FilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getFilename
     */
    public function testGetFilename()
    {
        $dir = \realpath(__DIR__.'/../../nstst/finders');
        $finder = new Files($dir, 'txt');
        $this->assertSame($dir.'/one.txt', $finder->getFilename('one'));
        $this->assertSame($dir.'/two.txt', $finder->getFilename('two'));
        $this->assertNull($finder->getFilename('three'));
        $this->assertNull($finder->getFilename('four'));
        $this->assertNull($finder->getFilename('five'));
    }

    /**
     * @covers ::getList
     */
    public function testGetList()
    {
        $dir = \realpath(__DIR__.'/../../nstst/finders');
        $finder = new Files($dir, 'txt');
        $list = $finder->getList();
        \sort($list);
        $this->assertEquals(['one', 'two'], $list);
        $list2 = $finder->getList();
        \sort($list2);
        $this->assertEquals($list, $list2);
    }

    /**
     * @covers ::getFilename
     * @covers ::getList
     */
    public function testEmptyExt()
    {
        $dir = \realpath(__DIR__.'/../../nstst/finders');
        $finder = new Files($dir);
        $this->assertSame($dir.'/four', $finder->getFilename('four'));
        $this->assertNull($finder->getFilename('one'));
        $this->assertEquals(['four'], $finder->getList());
    }
}
