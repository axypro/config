<?php

$parent = \axy\config\Loader::getParent(false);

return [
    'b' => $parent['a'] + $parent['b'],
    'c' => 11,
];
