<?php

$parent = $this->getParent(true);

return [
    'b' => $parent['a'] + $parent['b'],
];
