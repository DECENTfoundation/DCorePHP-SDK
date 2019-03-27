<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use PHPUnit\Framework\TestCase;

class AssetAmountTest extends TestCase
{
    public function testToBytes()
    {
        $assetAmount = new AssetAmount();

        $this->assertEquals(
            '000000000000000000',
            $assetAmount->toBytes()
        );

        $assetAmount
            ->setAssetId(new ChainObject('1.3.0'))
            ->setAmount(500000);

        $this->assertEquals(
            '20a107000000000000',
            $assetAmount->toBytes()
        );
    }
}