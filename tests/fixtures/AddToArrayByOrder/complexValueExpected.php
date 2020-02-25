<?php
/**
 * @author Mougrim <rinat@mougrim.ru>
 */

use Tests\CrmPlease\Coder\fixtures\FooClass;

$country = new stdClass();
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
    [2, 'string', \Tests\CrmPlease\Coder\fixtures\BarClass::class, \Tests\CrmPlease\Coder\fixtures\BarClass::TEST, Rule::unique('countries')->ignore($country->getKey())],
];
