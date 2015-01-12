<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\helpers;

use axy\config\helpers\Merger;

/**
 * @coversDefaultClass axy\config\helpers\Merger
 */
class MergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::merge
     * @dataProvider providerMerge
     * @param mixed $base
     * @param mixed $ext
     * @param mixed $expected
     */
    public function testMerge($base, $ext, $expected)
    {
        $actual = Merger::merge($base, $ext);
        if (is_array($expected)) {
            $this->assertEquals($expected, $actual);
        } else {
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * @return array
     */
    public function providerMerge()
    {
        return [
            [
                [
                    'one' => 1,
                    'two' => [
                        'a' => 'first',
                        'b' => 'second',
                        'c' => [1, 2, 3],
                    ],
                    'three' => 3,
                    'four' => [1, 2, 3]
                ],
                [
                    'one' => [1, 2],
                    'two' => [
                        'a' => 11,
                        'c' => [4],
                        'd' => 17,
                    ],
                    'four' => 1,
                    'five' => [1, 2],
                ],
                [
                    'one' => [1, 2],
                    'two' => [
                        'a' => 11,
                        'b' => 'second',
                        'c' => [4, 2, 3],
                        'd' => 17,
                    ],
                    'three' => 3,
                    'four' => 1,
                    'five' => [1, 2],
                ],
            ],
            [
                true,
                false,
                false,
            ],
            [
                true,
                [
                ],
                [
                ],
            ],
            [
                true,
                [
                    'enabled' => true,
                ],
                [
                    'enabled' => true,
                ],
            ],
            [
                [
                    'enabled' => true,
                ],
                null,
                null,
            ],
        ];
    }
}
