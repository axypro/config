<?php

namespace axy\config\tests\tst\external;

use axy\config\IExternal;

class TestExternal implements IExternal
{
    /**
     * {@inheritdoc}
     */
    public function isExists($key)
    {
        return (in_array($key, ['one', 'three']));
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($key === 'one') {
            return [
                'b' => 22,
                'd' => [
                    'f' => 55,
                    'h' => 77,
                ],
                'i' => 88,
            ];
        }
        if ($key === 'three') {
            return [
                'qq' => 'w',
                'ww' => 'q',
            ];
        }
        return null;
    }
}
