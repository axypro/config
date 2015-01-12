<?php
/**
 * Access to the system configuration
 *
 * @package axy\config
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 * @license https://raw.github.com/axypro/config/master/LICENSE MIT
 * @link https://github.com/axypro/config repository
 * @uses PHP5.4+
 */

namespace axy\config;

if (!is_file(__DIR__.'/vendor/autoload.php')) {
    throw new \LogicException('Please: composer install');
}

require_once(__DIR__.'/vendor/autoload.php');
