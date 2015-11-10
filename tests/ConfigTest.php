<?php
/**
 * @package axy/config
 */

namespace axy\config\tests;

use axy\config\Config;
use axy\config\helpers\Log;

/**
 * coversDefaultClass axy\config\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $dir;

    /**
     * @param callable $defParent [optional]
     * @return \axy\config\Config
     */
    private function createConfig($defParent = null)
    {
        $this->dir = __DIR__.'/tst/config';
        $settings = [
            'dir' => $this->dir,
        ];
        if ($defParent) {
            $settings['defparent'] = $defParent;
        }
        return new Config($settings);
    }

    /**
     * covers ::__construct
     * @expectedException \axy\config\errors\SettingsInvalidFormat
     */
    public function testConstruct()
    {
        return new Config([]);
    }

    /**
     * covers ::getListPlatforms
     */
    public function testGetListPlatforms()
    {
        Log::reset();
        $config = $this->createConfig();
        $list = $config->getListPlatforms();
        sort($list);
        $this->assertEquals(['base', 'dev', 'one', 'two'], $list);
        $expected = [
            'glob:'.$this->dir.'/*',
        ];
        $this->assertEquals($expected, Log::get());
    }

    /**
     * covers ::isPlatformExists
     */
    public function testIsPlatformExists()
    {
        Log::reset();
        $config = $this->createConfig();
        $this->assertTrue($config->isPlatformExists('dev'));
        $this->assertFalse($config->isPlatformExists('unDev'));
        $this->assertTrue($config->isPlatformExists('dev'));
        $this->assertFalse($config->isPlatformExists('unDev'));
        $expected = [
            'is_dir:'.$this->dir.'/dev',
            'is_dir:'.$this->dir.'/unDev',
        ];
        $this->assertEquals($expected, Log::get());
    }

    /**
     * covers ::getConfigForPlatform
     * @expectedException \axy\config\errors\PlatformNotExists
     */
    public function testErrorPlatformNotExists()
    {
        $this->createConfig()->getConfigForPlatform('unDev');
    }

    /**
     * covers ::getConfigForPlatform
     */
    public function testRootPlatform()
    {
        Log::reset();
        $container = $this->createConfig();
        $config = $container->getConfigForPlatform('base');
        $this->assertInstanceOf('axy\config\IRootNode', $config);
        $this->assertSame('base', $config->getPlatformName());
        $this->assertSame($container, $config->getConfigContainer());
        $this->assertNull($config->getParentPlatform());
        $this->assertSame($config, $container->getConfigForPlatform('base'));
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertSame('base', $config->scalar);
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertSame(1, $config->arr->arr->a);
        $this->assertSame(2, $config->get('arr.arr.b.1'));
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertSame('arr.arr.b', $config->arr->arr->b->getPath());
        $this->assertSame($config, $config->getRootNode());
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertSame($config, $config->arr->arr->b->getRootNode());
        $expected = [
            'is_dir:'.$this->dir.'/base',
            'is_file:'.$this->dir.'/base/scalar.php',
            'include:'.$this->dir.'/base/scalar.php',
            'is_file:'.$this->dir.'/base/arr.php',
            'include:'.$this->dir.'/base/arr.php',
        ];
        $this->assertEquals($expected, Log::get());
    }

    /**
     * covers ::getConfigForPlatform
     */
    public function testInheritPlatform()
    {
        $container = $this->createConfig();
        $config = $container->getConfigForPlatform('one');
        $this->assertInstanceOf('axy\config\IRootNode', $config);
        $this->assertSame('one', $config->getPlatformName());
        $base = $config->getParentPlatform();
        $this->assertInstanceOf('axy\config\IRootNode', $base);
        $this->assertSame('base', $base->getPlatformName());
        $this->assertSame($base, $container->getConfigForPlatform('base'));
        $expected = [
            'arr' => [
                'one' => 'first',
                'two' => 2,
                'null' => null,
                'arr' => [
                    'a' => 2,
                    'b' => [1, 2, 3],
                ],
                'x' => 'x',
            ],
            'scalar' => 'one',
            'null' => null,
            'n' => [1, 2],
        ];
        $this->assertEquals($expected, $config->getValue());
    }

    /**
     * covers ::getConfigForPlatform
     */
    public function testMultiInheritPlatform()
    {
        $defparent = function ($name) {
            if ($name === 'two') {
                return 'dev';
            } elseif ($name === 'base') {
                return null;
            }
            return 'base';
        };
        $container = $this->createConfig($defparent);
        $one = $container->getConfigForPlatform('one');
        $two = $container->getConfigForPlatform('two');
        $base = $container->getConfigForPlatform('base');
        $dev = $container->getConfigForPlatform('dev');
        $this->assertSame($base, $one->getParentPlatform());
        $this->assertSame($base, $dev->getParentPlatform());
        $this->assertSame($dev, $two->getParentPlatform());
        $this->assertSame($base, $two->getParentPlatform()->getParentPlatform());
        $this->assertNull($base->getParentPlatform());
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertSame('x', $two->x);
        $arr = [
            'one' => 'dev',
            'two' => 2,
            'null' => null,
            'arr' => [
                'a' => 1,
                'b' => [1, 2, 3],
            ],
            'dev' => 'dev',
        ];
        /** @noinspection PhpUndefinedFieldInspection */
        $this->assertEquals($arr, $two->arr->getValue());
        $expected = [
            'arr' => $arr,
            'scalar' => null,
            'x' => 'x',
            'dev' => [1, 2],
            'null' => null,
        ];
        $this->assertEquals($expected, $two->getValue());
    }

    /**
     * covers ::getConfigForPlatform
     */
    public function testLoader()
    {
        $defparent = function ($name) {
            if ($name === 'base') {
                return null;
            } elseif ($name === 'dev') {
                return 'int';
            }
            return 'base';
        };
        $settings = [
            'dir' => __DIR__.'/tst/config-m',
            'defparent' => $defparent,
        ];
        $container = new Config($settings);
        $config = $container->getConfigForPlatform('dev');
        $expected = [
            'one' => [
                'c' => 11,
            ],
            'two' => [
                'b' => 3,
            ],
            'three' => [
                'a' => 1,
                'b' => 3,
                'c' => 11,
            ],
            'four' => [
                'c' => 4,
                'd' => 5,
            ],
        ];
        $this->assertEquals($expected, $config->getValue());
    }

    /**
     * covers ::getConfigForPlatform
     */
    public function testCacheData()
    {
        $container = $this->createConfig();
        $base = $container->getConfigForPlatform('base');
        $one = $container->getConfigForPlatform('one');
        Log::reset();
        $one->get('arr');
        $expected = [
            'is_file:'.$this->dir.'/one/arr.php',
            'include:'.$this->dir.'/one/arr.php',
            'is_file:'.$this->dir.'/base/arr.php',
            'include:'.$this->dir.'/base/arr.php',
        ];
        $this->assertEquals($expected, Log::get(true));
        $base->get('arr');
        $this->assertEquals([], Log::get(true));
    }
}
