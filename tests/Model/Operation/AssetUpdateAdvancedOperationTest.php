<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetUpdateAdvancedOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetUpdateAdvancedOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new AssetUpdateAdvancedOperation();
        $operation
            ->setIssuer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setPrecision(6)
            ->setFixedMaxSupply(false)
            ->setAssetToUpdate(new ChainObject('1.3.225'))
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '280000000000000000001be101060000',
            $operation->toBytes()
        );
    }
}