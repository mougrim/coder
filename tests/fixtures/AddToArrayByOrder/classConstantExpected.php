<?php
/**
 * @author Mougrim <rinat@mougrim.ru>
 */

use Tests\CrmPlease\Coder\fixtures\FooClass;

return [
    null,
    false,
    true,
    0,
    1,
    0.0,
    0.5,
    '',
    'null',
    'false',
    'true',
    '0',
    '1',
    '2',
    'test',
    FooClass::class,
    FooClass::TEST,
    \Tests\CrmPlease\Coder\fixtures\BarClass::class,
];
