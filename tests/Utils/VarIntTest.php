<?php

namespace DCorePHPTests\Utils;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use PHPUnit\Framework\TestCase;

class VarIntTest extends TestCase
{

    public function testWriteUnsignedVarIntHex(): void
    {
        $this->assertEquals('ac02', VarInt::encodeDecToHex(300));
    }

}