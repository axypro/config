<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\node;

use axy\config\tests\nstst\Node;

/**
 * @coversDefaultClass axy\config\nodes\Base
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getPath
     */
    public function testGetPath()
    {
        $node = new Node('one.two');
        $this->assertSame('one.two', $node->getPath());
    }

    public function testToString()
    {
        $node = new Node('one.two');
        $this->assertSame('[one.two]', (string)$node);
    }

    public function testExists()
    {
        $node = new Node('');
        $this->assertTrue($node->exists('one'));
        $this->assertTrue($node->exists('two'));
        $this->assertTrue($node->exists('one.one.three'));
        $this->assertFalse($node->exists('four'));
        $this->assertFalse($node->exists('four.five'));
        $this->assertFalse($node->exists('one.four.five'));
    }

    public function testGet()
    {
        $node = new Node('');
        $one = $node->get('one');
        $this->assertInstanceOf('axy\config\INode', $one);
        $this->assertSame($one, $node->get('one'));
        $this->assertSame(2, $node->get('two'));
        $this->assertSame(3, $node->get('three'));
        $this->assertSame(3, $node->get('one.one.three'));
        $this->assertSame(3, $node->get(['one', 'one', 'three']));
        $this->assertSame(3, $one->get('one.three'));
        $this->setExpectedException('axy\config\errors\ConfigNodeNotExists');
        $node->get('one.five');
    }

    public function testGetValue()
    {
        $node = new Node('');
        $expected = [
            'one' => [
                'one' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'two' => 2,
                'three' => 3,
            ],
            'two' => 2,
            'three' => 3,
        ];
        $this->assertEquals($expected, $node->getValue());
    }

    public function testMagic()
    {
        $node = new Node('');
        $this->assertTrue(isset($node->one));
        $this->assertTrue(isset($node->two));
        $this->assertFalse(isset($node->five));
        $one = $node->one;
        $this->assertInstanceOf('axy\config\INode', $one);
        $this->assertSame($one, $node->one);
        $this->assertSame(2, $node->two);
        $this->assertSame(3, $node->three);
        $this->assertSame(2, $node->one->two);
        $this->setExpectedException('axy\config\errors\ConfigNodeNotExists');
        return $node->five;
    }

    public function testAA()
    {
        $node = new Node('');
        $this->assertTrue(isset($node['one']));
        $this->assertTrue(isset($node['two']));
        $this->assertFalse(isset($node['five']));
        $one = $node['one'];
        $this->assertInstanceOf('axy\config\INode', $one);
        $this->assertSame($one, $node['one']);
        $this->assertSame(2, $node['two']);
        $this->assertSame(3, $node['three']);
        $this->assertSame(2, $node['one']['two']);
        $this->setExpectedException('axy\config\errors\ConfigNodeNotExists');
        return $node['five'];
    }

    /**
     * @dataProvider providerReadonly
     * @param callable $f
     * @expectedException axy\errors\ContainerReadonly
     */
    public function testReadonly($f)
    {
        $node = new Node('');
        $f($node);
    }

    /**
     * @return array
     */
    public function providerReadonly()
    {
        return [
            [
                function ($node) {
                    $node->x = 1;
                },
            ],
            [
                function ($node) {
                    unset($node->x);
                },
            ],
            [
                function ($node) {
                    $node['x'] = 1;
                },
            ],
            [
                function ($node) {
                    unset($node['x']);
                },
            ],
        ];
    }

    public function testCountable()
    {
        $node = new Node('');
        $this->assertCount(3, $node);
    }

    public function testIterator()
    {
        $node = new Node('');
        $one = $node->one;
        $expected = [
            'one' => $one,
            'two' => 2,
            'three' => 3,
        ];
        $this->assertEquals($expected, iterator_to_array($node));
    }
}
