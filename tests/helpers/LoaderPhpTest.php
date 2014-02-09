<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\helpers;

use axy\config\helpers\LoaderPhp;
use axy\config\helpers\Log;

/**
 * @coversDefaultClass axy\config\helpers\LoaderPhp
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::load
     * @covers ::noMerge
     * @covers ::getParent
     * @dataProvider providerLoad
     * @param string $file
     * @param mixed $parent
     * @param mixed $expected
     */
    public function testLoad($file, $parent, $expected)
    {
        Log::reset();
        $filename = __DIR__.'/../nstst/loader/'.$file.'.php';
        if ($parent !== null) {
            $getparent = function () use ($parent) {
                return $parent;
            };
        } else {
            $getparent = null;
        }
        $loader = new LoaderPhp($filename, $getparent);
        $actual = $loader->load();
        if (\is_array($expected)) {
            $this->assertEquals($expected, $actual);
        } else {
            $this->assertSame($expected, $actual);
        }
        $expected = [
            'include:'.$filename,
        ];
        $this->assertEquals($expected, Log::get());
    }

    /**
     * @return array
     */
    public function providerLoad()
    {
        return [
            [
                'merge',
                [
                    'a' => [1, 2],
                    'b' => [
                        'e' => null,
                        'h' => 'h',
                    ],
                ],
                [
                    'a' => 1,
                    'b' => [
                        'd' => 5,
                        'e' => [1, 2, 3],
                        'h' => 'h',
                    ],
                    'c' => null,
                ],
            ],
            [
                'scalar',
                [
                    'a' => 1,
                    'b' => 2,
                ],
                'this is scalar',
            ],
            [
                'root',
                null,
                [
                    'a' => 1,
                    'b' => 2,
                ],
            ],
            [
                'nomerge',
                [
                    'a' => 1,
                    'b' => 2,
                ],
                [
                    'a' => 3,
                    'e' => 5,
                ]
            ],
            [
                'getparent',
                [
                    'a' => 1,
                    'b' => 2,
                ],
                [
                    'c' => 3,
                ],
            ],
            [
                'getparent-merge',
                [
                    'a' => 1,
                    'b' => 2,
                ],
                [
                    'a' => 1,
                    'b' => 3,
                ],
            ],
        ];
    }
}
