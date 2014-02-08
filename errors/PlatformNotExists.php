<?php
/**
 * @package axy\config
 */

namespace axy\config\errors;

class PlatformNotExists extends \axy\errors\FieldNotExist implements Error
{
    /**
     * {@inheritdoc}
     */
    protected $defaultMessage = 'Platform "{{ key }}" is not exist';

    /**
     * Constructor
     *
     * @param string $key [optional]
     * @param \Exception $previous [optional]
     * @param mixed $thrower [optional]
     */
    public function __construct($key = null, \Exception $previous = null, $thrower = null)
    {
        parent::__construct($key, 'Config', $previous, $thrower);
    }
}
