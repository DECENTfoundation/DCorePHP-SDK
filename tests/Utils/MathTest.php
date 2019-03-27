<?php

namespace DCorePHPTests\Utils;

use DCorePHP\Utils\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{

    public function testWriteUnsignedVarIntHex(): void
    {
        $this->assertEquals('9b01', Math::writeUnsignedVarIntHex(155));
    }

}