<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetFundPoolsOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetFundPoolsOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new AssetFundPoolsOperation();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setUia((new AssetAmount())->setAmount(0)->setAssetId(new ChainObject('1.3.41')))
            ->setDct((new AssetAmount())->setAmount(1000))
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '210000000000000000001b000000000000000029e8030000000000000000',
            $operation->toBytes()
        );

        $operation = new AssetFundPoolsOperation();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setUia((new AssetAmount())->setAmount(10)->setAssetId(new ChainObject('1.3.36')))
            ->setDct((new AssetAmount())->setAmount(10))
            ->setFee((new AssetAmount())->setAmount(10));

        $this->assertEquals(
            '210a00000000000000001b0a00000000000000240a000000000000000000',
            $operation->toBytes()
        );
    }
}