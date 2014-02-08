<?php
/**
 * @package axy/config
 */

namespace axy\config\tests;

use axy\config\Config;

/**
 * @coversDefaultClass axy\config\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param callable $defparent [optional]
     * @return \axy\config\Config
     */
    private function createConfig($defparent = null)
    {
        $settings = [
            'dir' => __DIR__.'/nstst/config',
        ];
        if ($defparent) {
            $settings['defparent'] = $defparent;
        }
        return new Config($settings);
    }

    /**
     * @covers ::__construct
     * @expectedException axy\config\errors\SettingsInvalidFormat
     */
    public function testConstruct()
    {
        return new Config([]);
    }

    /**
     * @covers ::getListPlatforms
     */
    public function testGetListPlatforms()
    {
        $config = $this->createConfig();
        $list = $config->getListPlatforms();
        \sort($list);
        $this->assertEquals(['base', 'dev', 'one', 'two'], $list);
    }

    /**
     * @covers ::isPlatformExists
     */
    public function testIsPlatformExists()
    {
        $config = $this->createConfig();
        $this->assertTrue($config->isPlatformExists('dev'));
        $this->assertFalse($config->isPlatformExists('undev'));
    }

    /**
     * @covers ::getConfigForPlatform
     * @expectedException axy\config\errors\PlatformNotExists
     */
    public function testErrorPlatformNotExists()
    {
        $this->createConfig()->getConfigForPlatform('undev');
    }

    /**
     * @covers ::getConfigForPlatform
     */
    public function testRootPlatform()
    {
        $container = $this->createConfig();
        $config = $container->getConfigForPlatform('base');
        $this->assertInstanceOf('axy\config\IRootNode', $config);
        $this->assertSame('base', $config->getPlatformName());
        $this->assertSame($container, $config->getConfigContainer());
        $this->assertNull($config->getParentPlatform());
        $this->assertSame($config, $container->getConfigForPlatform('base'));
        $this->assertSame('base', $config->scalar);
        $this->assertSame(1, $config->arr->arr->a);
        $this->assertSame(2, $config->get('arr.arr.b.1'));
        $this->assertSame('arr.arr.b', $config->arr->arr->b->getPath());
        $expected = [
            'arr' => [
                'one' => 'first',
                'two' => 2,
                'null' => null,
                'arr' => [
                    'a' => 1,
                    'b' => [1, 2, 3],
                ],
            ],
            'scalar' => 'base',
            'null' => null,
        ];
        $this->assertSame($config, $config->getRootNode());
        $this->assertSame($config, $config->arr->arr->b->getRootNode());
    }

    /**
     * @covers ::getConfigForPlatform
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
    }

    /**
     * @covers ::getConfigForPlatform
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
}
