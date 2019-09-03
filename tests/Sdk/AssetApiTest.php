<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetData;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\ChainObject;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class AssetApiTest extends DCoreSDKTest
{
    /** @var ChainObject */
    private $testAssetId;

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function setUp()
    {
        parent::setUp();

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK' . time() . 'T';
        self::$sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        $asset = self::$sdk->getAssetApi()->getByName($symbol);
        $this->testAssetId = $asset->getId();

        self::$sdk->getAssetApi()->issue($credentials, clone $this->testAssetId, 200);
        self::$sdk->getAssetApi()->fund($credentials, clone $this->testAssetId, 100, 1000);
        sleep(5);
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGet(): void
    {
        $asset = self::$sdk->getAssetApi()->get(new ChainObject('1.3.0'));
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGetAll(): void
    {
        $assets = self::$sdk->getAssetApi()->getAll([new ChainObject('1.3.0')]);

        $this->assertCount(1, $assets);

        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('1.3.0', $asset->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetRealSupply(): void
    {
        self::$sdk->getAssetApi()->getRealSupply();
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListAllRelative(): void
    {
        $assets = self::$sdk->getAssetApi()->listAllRelative('ALX');
        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetByName(): void
    {
        $asset = self::$sdk->getAssetApi()->getByName('DCT');

        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllByName(): void
    {
        $assets = self::$sdk->getAssetApi()->getAllByName(['DCT']);
        $asset = reset($assets);

        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAssetsData(): void
    {
        /** @var AssetData[] $assets */
        $assets = self::$sdk->getAssetApi()->getAssetsData([new ChainObject('2.3.0')]);
        $asset = reset($assets);

        $this->assertEquals('2.3.0', $asset->getId()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testConvertFromDct(): void
    {
        self::$sdk->getAssetApi()->convertFromDct(5, new ChainObject('1.3.0'));

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testConvertToDct(): void
    {
        $assetAmount = self::$sdk->getAssetApi()->convertToDct(5, new ChainObject('1.3.0'));

        $this->assertEquals(5, $assetAmount->getAmount());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId());
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testCreate(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK' . time() . 'T';
        self::$sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        $asset = self::$sdk->getAssetApi()->getByName($symbol);

        $this->assertNotNull($asset);
        $this->assertEquals(AssetOptions::MAX_SHARE_SUPPLY, $asset->getOptions()->getMaxSupply());
        $this->assertTrue($asset->getOptions()->isExchangeable());
    }

    /**
     * @depends testCreate
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK' . time() . 'T';
        self::$sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        sleep(3);

        $old = self::$sdk->getAssetApi()->getByName($symbol);

        self::$sdk->getAssetApi()->update(
            $credentials,
            $symbol,
            new ExchangeRate((new AssetAmount())->setAmount(1), (new AssetAmount())->setAmount(2)->setAssetId($old->getId())),
            $old->getDescription() . ' update from PHP',
            true,
            gmp_div($old->getOptions()->getMaxSupply(), 2)
            );

        $new = self::$sdk->getAssetApi()->getByName($symbol);

        $this->assertEquals($old->getDescription() . ' update from PHP', $new->getDescription());
        $this->assertTrue($new->getOptions()->isExchangeable());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdateAdvanced(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK' . time() . 'T';
        self::$sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        self::$sdk->getAssetApi()->updateAdvanced(
            $credentials,
            $symbol,
            6,
            false
        );

        $newAsset = self::$sdk->getAssetApi()->getByName($symbol);
        $this->assertEquals(6, $newAsset->getPrecision());
    }

    /**
     * @depends testCreate
     * @throws ValidationException
     * @throws Exception
     */
    public function testIssue(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $asset = self::$sdk->getAssetApi()->get(clone $this->testAssetId);
        /** @var AssetData[] $oldData */
        $oldData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        self::$sdk->getAssetApi()->issue($credentials, clone $this->testAssetId, 200);

        /** @var AssetData[] $newData */
        $newData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);

        $this->assertEquals(200, $newData->getCurrentSupply() - $oldData->getCurrentSupply());
    }

    /**
     * @depends testCreate
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public function testFund(): void
    {
        $asset = self::$sdk->getAssetApi()->get(clone $this->testAssetId);
        /** @var AssetData[] $oldData */
        $oldData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getAssetApi()->fund($credentials, clone $this->testAssetId, 100, 1000);

        /** @var AssetData[] $newData */
        $newData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(1000, $newData->getCorePool() - $oldData->getCorePool());
    }

    /**
     * @depends testCreate
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testClaim(): void
    {
        $asset = self::$sdk->getAssetApi()->get(clone $this->testAssetId);
        /** @var AssetData[] $oldData */
        $oldData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getAssetApi()->claim($credentials, clone $this->testAssetId, 1, 0);

        /** @var AssetData[] $newData */
        $newData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(1, $oldData->getAssetPool() - $newData->getAssetPool());
    }

    /**
     * @depends testCreate
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testReserve(): void
    {
        $asset = self::$sdk->getAssetApi()->get(clone $this->testAssetId);
        /** @var AssetData[] $oldData */
        $oldData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getAssetApi()->reserve($credentials, clone $this->testAssetId, 100);

        /** @var AssetData[] $newData */
        $newData = self::$sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(100, $oldData->getCurrentSupply() - $newData->getCurrentSupply());
    }
}
