<?php
/**
 * @author Mougrim <rinat@mougrim.ru>
 */

return [
    'path1' => [
        'value1',
        'key2' => 'value2',
        'closure' => function ($param) {
            return $param ?: null;
        },
        'value3',
        'value4',
        'key5' => 'value5',
    ],
];
