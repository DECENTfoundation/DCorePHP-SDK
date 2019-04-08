<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\ChainObject;
use PHPUnit\Framework\TestCase;

class ChainObjectTest extends TestCase
{
    public function testToBytes(): void
    {
        $this->assertEquals(
            '1c',
            (new ChainObject('1.2.28'))->toBytes()
        );

        $this->assertEquals(
            '23',
            (new ChainObject('1.2.35'))->toBytes()
        );
    }

    public function testToObjectTypeIdBytes(): void
    {
        $this->assertEquals(
            '1c00000000000201',
            (new ChainObject('1.2.28'))->toObjectTypeIdBytes()
        );

        $this->assertEquals(
            '2300000000000201',
            (new ChainObject('1.2.35'))->toObjectTypeIdBytes()
        );
    }
}
