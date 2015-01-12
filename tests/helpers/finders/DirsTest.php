<?php
/**
 * @package axy/config
 */

namespace axy\config\tests\helpers\finders;

use axy\config\helpers\finders\Dirs;
use axy\config\helpers\Log;

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
        Log::reset();
        $dir = realpath(__DIR__.'/../../nstst/finders');
        $finder = new Dirs($dir, 'txt');
        $this->assertSame($dir.'/two', $finder->getFilename('two'));
        $this->assertSame($dir.'/three', $finder->getFilename('three'));
        $this->assertNull($finder->getFilename('one'));
        $this->assertNull($finder->getFilename('four'));
        $this->assertNull($finder->getFilename('five'));
        $this->assertSame($dir.'/three', $finder->getFilename('three'));
        $this->assertNull($finder->getFilename('four'));
        $expected = [
            'is_dir:'.$dir.'/two',
            'is_dir:'.$dir.'/three',
            'is_dir:'.$dir.'/one',
            'is_dir:'.$dir.'/four',
            'is_dir:'.$dir.'/five',
        ];
        $this->assertEquals($expected, Log::get());
    }

    /**
     * @covers ::getList
     */
    public function testGetList()
    {
        Log::reset();
        $dir = realpath(__DIR__.'/../../nstst/finders');
        $finder = new Dirs($dir, 'txt');
        $list = $finder->getList();
        sort($list);
        $this->assertEquals(['three', 'two'], $list);
        $list2 = $finder->getList();
        sort($list2);
        $this->assertEquals($list, $list2);
        $this->assertSame($dir.'/three', $finder->getFilename('three'));
        $this->assertNull($finder->getFilename('four'));
        $expected = [
            'glob:'.$dir.'/*',
        ];
        $this->assertEquals($expected, Log::get());

    }
}
