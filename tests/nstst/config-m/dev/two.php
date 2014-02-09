<?php

$parent = \axy\config\Loader::getParent(true);

return [
    'b' => $parent['a'] + $parent['b'],
];
