<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetReserveOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetReserveOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {

        $operation = new AssetReserveOperation();
        $operation
            ->setPayer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setAmount((new AssetAmount())->setAmount(100)->setAssetId(new ChainObject('1.3.41')))
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '220000000000000000001b64000000000000002900',
            $operation->toBytes()
        );
    }
}