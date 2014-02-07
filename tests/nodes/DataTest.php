<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\node;

use axy\config\nodes\Data;

/**
 * @coversDefaultClass axy\config\nodes\Data
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    public function testData()
    {
        $data = [
            'one' => 1,
            'two' => 'This is two',
            'three' => [
                'four' => 4,
                'five' => [5, 6, 7],
                '' => 'empty',
            ],
            'n' => null,
        ];
        $node = new Data($data, 'path');
        $this->assertSame('path', $node->getPath());
        $this->assertTrue(isset($node->one));
        $this->assertTrue(isset($node->n));
        $this->assertFalse(isset($node->five));
        $three = $node->three;
        $this->assertSame($three, $node['three']);
        $this->assertSame($three, $node->get('three'));
        $this->assertSame(6, $node->three->five[1]);
        $this->assertSame(4, $node->get('three.four'));
        $this->assertNull($node->get('n'));
        $this->assertCount(4, $node);
        $this->assertEquals($data, $node->getValue());
        $this->assertSame('empty', $three->get(''));
        $this->assertSame('empty', $node->get('three.'));
        $this->assertSame('[path.three.five]', (string)$node->three->five);
        $this->setExpectedException('axy\config\errors\ConfigNodeNotExists');
        return $node->five;
    }
}
