<?php
declare(strict_types=1);

namespace Tests\CrmPlease\Coder\Functional;

use Tests\CrmPlease\Coder\fixtures\BarClass;
use Tests\CrmPlease\Coder\fixtures\FooClass;
use Tests\CrmPlease\Coder\FunctionalTestCase;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class ChangeClassParentTest extends FunctionalTestCase
{
    public function testChangeParent(): void
    {
        $fixture = 'ChangeParent';
        $coder = $this->getCoder();
        $coder->changeClassParent(
            $this->createFixtureFile($fixture),
            BarClass::class
        );
        $this->assertFixture($fixture);
    }

    public function testSameParent(): void
    {
        $fixture = 'SameParent';
        $coder = $this->getCoder();
        $coder->changeClassParent(
            $this->createFixtureFile($fixture),
            FooClass::class
        );
        $this->assertFixture($fixture);
    }
}
