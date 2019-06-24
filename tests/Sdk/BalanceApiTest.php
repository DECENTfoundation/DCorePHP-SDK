<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHPTests\DCoreSDKTest;

class BalanceApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGet(): void
    {
        $asset = $this->sdk->getBalanceApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.3.56576'));

        $this->assertEquals('1.3.56576', $asset->getAssetId()->getId());
        $this->assertEquals(0, $asset->getAmount());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAll(): void
    {
        /** @var AssetAmount[] $balances */
        $balances = $this->sdk->getBalanceApi()->getAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        $this->assertInternalType('array', $balances);

        foreach ($balances as $balance) {
            $this->assertRegExp('/^\d+\.\d+\.\d+$/', $balance->getAssetId());
            $this->assertRegExp('/^\d+$/', $balance->getAmount());
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetByName(): void
    {
        $asset = $this->sdk->getBalanceApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1, new ChainObject('1.3.0'));

        $this->assertEquals('1.3.0', $asset->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllByName(): void
    {
        /** @var AssetAmount[] $assets */
        $assets = $this->sdk->getBalanceApi()->getAllByName(DCoreSDKTest::ACCOUNT_NAME_1, [new ChainObject('1.3.0')]);

        $this->assertEquals('1.3.0', $assets[0]->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetWithAsset(): void
    {
        [$asset, $assetAmount] = $this->sdk->getBalanceApi()->getWithAsset(new ChainObject('1.2.34'));

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    public function testGetAllWithAsset(): void
    {
        $assetPairs = $this->sdk->getBalanceApi()->getAllWithAsset(new ChainObject('1.2.34'), ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    public function testGetWithAssetByName(): void
    {
        [$asset, $assetAmount] = $this->sdk->getBalanceApi()->getWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    public function testGetAllWithAssetByName(): void
    {
        $assetPairs = $this->sdk->getBalanceApi()->getAllWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1, ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllVesting(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}
