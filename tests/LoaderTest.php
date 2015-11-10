<?php
/**
 * @package axy/config
 */

namespace axy\config\tests;

use axy\config\Loader;
use axy\config\helpers\SetterLoader;
use axy\config\tests\tst\LoaderMock;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers axy\config\Loader::notMerge
     * covers axy\config\Loader::getParent
     * covers axy\config\helpers\SetterLoader::push
     * covers axy\config\helpers\SetterLoader::pop
     */
    public function testLoader()
    {
        LoaderMock::$log = [];
        $l1 = new LoaderMock('a');
        $l2 = new LoaderMock('b');
        $l3 = new LoaderMock('c');
        SetterLoader::push($l1);
        Loader::notMerge();
        SetterLoader::push($l2);
        Loader::getParent();
        Loader::notMerge();
        SetterLoader::push($l3);
        Loader::getParent(false);
        $this->assertSame($l3, SetterLoader::pop());
        $this->assertSame($l2, SetterLoader::pop());
        Loader::notMerge();
        $this->assertSame($l1, SetterLoader::pop());
        $expected = [
            'a:notMerge',
            'b:getParent:1',
            'b:notMerge',
            'c:getParent:0',
            'a:notMerge',
        ];
        $this->assertEquals($expected, LoaderMock::$log);
    }
}
