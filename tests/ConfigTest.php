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
     * @return \axy\config\Config
     */
    private function createConfig()
    {
        return new Config(['dir' => __DIR__.'/nstst/config']);
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
}
