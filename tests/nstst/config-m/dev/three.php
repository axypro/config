<?php

$parent = $this->getParent(false);

return [
    'b' => $parent['a'] + $parent['b'],
    'c' => 11,
];
