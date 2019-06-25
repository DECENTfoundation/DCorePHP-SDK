<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetCreateOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetCreateOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {

        $operation = new AssetCreateOperation();
        $operation
            ->setIssuer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setSymbol('SDK.1558613201T')
            ->setPrecision(12)
            ->setDescription('hello api from PHP')
            ->setOptions(
                (new AssetOptions())
                    ->setMaxSupply('7319777577456890')
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(1)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1)->setAssetId('1.3.1')))
                    ->setExchangeable(true)
                    ->setExtensions([['is_fixed_max_supply' => false]])
            )
            ->setMonitoredOptions(null)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '030000000000000000001b0f53444b2e31353538363133323031540c1268656c6c6f206170692066726f6d20504850fad456864c011a0001000000000000000001000000000000000101010100000100',
            $operation->toBytes()
        );
    }
}