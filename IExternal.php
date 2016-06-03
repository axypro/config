<?php

namespace axy\config;

interface IExternal
{
    /**
     * @param string $key
     * @return bool
     */
    public function isExists($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);
}
