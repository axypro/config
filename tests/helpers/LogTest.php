<?php
/**
 * @package axy\config
 */

namespace axy\config\tests\helpers;

use axy\config\helpers\Log;

/**
 * @coversDefaultClass axy\config\helpers\Log
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::write
     * @covers ::reset
     * @covers ::get
     */
    public function testLog()
    {
        Log::reset();
        $this->assertEquals([], Log::get());
        Log::write('one');
        Log::write('two');
        $this->assertEquals(['one', 'two'], Log::get());
        $this->assertEquals([], Log::get());
        Log::write('three');
        Log::write('four');
        $this->assertEquals(['three', 'four'], Log::get(false));
        Log::write('five');
        $this->assertEquals(['three', 'four', 'five'], Log::get(false));
        Log::reset();
        $this->assertEquals([], Log::get(false));
    }
}
