<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\VestingBalance;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class BalanceApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGet(): void
    {
        $asset = self::$sdk->getBalanceApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.3.56576'));

        $this->assertEquals('1.3.56576', $asset->getAssetId()->getId());
        $this->assertEquals(0, $asset->getAmount());
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetAll(): void
    {
        /** @var AssetAmount[] $balances */
        $balances = self::$sdk->getBalanceApi()->getAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($balances as $balance) {
            $this->assertRegExp('/^\d+\.\d+\.\d+$/', $balance->getAssetId());
            $this->assertRegExp('/^\d+$/', $balance->getAmount());
        }
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetByName(): void
    {
        $asset = self::$sdk->getBalanceApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1, new ChainObject('1.3.0'));

        $this->assertEquals('1.3.0', $asset->getAssetId()->getId());
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetAllByName(): void
    {
        /** @var AssetAmount[] $assets */
        $assets = self::$sdk->getBalanceApi()->getAllByName(DCoreSDKTest::ACCOUNT_NAME_1, [new ChainObject('1.3.0')]);

        $this->assertEquals('1.3.0', $assets[0]->getAssetId()->getId());
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetWithAsset(): void
    {
        [$asset, $assetAmount] = self::$sdk->getBalanceApi()->getWithAsset(new ChainObject('1.2.34'));

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    public function testGetAllWithAsset(): void
    {
        $assetPairs = self::$sdk->getBalanceApi()->getAllWithAsset(new ChainObject('1.2.34'), ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    public function testGetWithAssetByName(): void
    {
        [$asset, $assetAmount] = self::$sdk->getBalanceApi()->getWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllWithAssetByName(): void
    {
        $assetPairs = self::$sdk->getBalanceApi()->getAllWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1, ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     *
     * @doesNotPerformAssertions
     */
    public function testGetAllVesting(): void
    {
        self::$sdk->getBalanceApi()->getAllVesting(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
    }
}
