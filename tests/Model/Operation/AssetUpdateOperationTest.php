<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetUpdateOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetUpdateOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new AssetUpdateOperation();
        $operation
            ->setIssuer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setAssetToUpdate(new ChainObject('1.3.223'))
            ->setCoreExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(1)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(2)->setAssetId('1.3.223')))
            ->setNewDescription('hello api from PHP update from PHP')
            ->setExchangeable(true)
            ->setMaxSupply('3659888788728445')
            ->setNewIssuer(null)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '240000000000000000001bdf012268656c6c6f206170692066726f6d20504850207570646174652066726f6d20504850007d6a2b43a6000d000100000000000000000200000000000000df010100',
            $operation->toBytes()
        );
    }
}