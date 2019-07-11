<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Nft;
use DCorePHP\Model\NftData;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Model\Variant;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use WebSocket\BadOpcodeException;

class NftApiTest extends DCoreSDKTest
{
    /** @var ChainObject */
    private static $nftId;
    /** @var string */
    private static $symbol;
    /** @var string */
    private static $nftIssuedId;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::testCreate();
        self::testUpdate();
        self::testIssue();
        self::testTransfer();
        self::testUpdateData();
        self::testUpdateDataRaw();

        sleep(5);
    }

    public static function testCreate(): void
    {
        self::$symbol = 'APPLE' . time() . 'T';
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getNftApi()->create($credentials, self::$symbol, 100, false, 'an apple', new NftApple(), true);

        $nft = self::$sdk->getNftApi()->getBySymbol(self::$symbol);
        self::$nftId = $nft->getId();

        self::assertNotNull($nft);
        self::assertEquals(100, $nft->getOptions()->getMaxSupply());
        self::assertFalse($nft->getOptions()->isFixedMaxSupply());
    }

    public static function testUpdate(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getNftApi()->update($credentials, self::$symbol, null, null, 'an apple update');

        $updatedNft = self::$sdk->getNftApi()->getBySymbol(self::$symbol);
        self::assertEquals('an apple update', $updatedNft->getOptions()->getDescription());
    }

    public static function testIssue(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $apple = new NftApple(5, 'red', false);
        $notice = self::$sdk->getNftApi()->issue($credentials, self::$symbol, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), $apple);
        self::$nftIssuedId = $notice->getTransaction()->getOpResults()[0][1];

        $issuedNft = self::$sdk->getNftApi()->getBySymbol(self::$symbol);
        self::assertEquals(1, $issuedNft->getCurrentSupply());
    }

    public static function testTransfer(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getNftApi()->transfer($credentials, new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), new ChainObject(self::$nftIssuedId));

        $issuedNft = self::$sdk->getNftApi()->getBySymbol(self::$symbol);
        self::assertEquals(1, $issuedNft->getCurrentSupply());
    }

    public static function testUpdateData(): void
    {
        $nftData = self::$sdk->getNftApi()->getDataWithClass(new ChainObject(self::$nftIssuedId), NftApple::class);
        /** @var NftApple $data */
        $data = $nftData->getData();
        $eaten = $data->getEaten()->getValue();
        $data->getEaten()->setValue(!$data->getEaten()->getValue());

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getNftApi()->updateData($credentials, $nftData->getId(), $data);

        $nftDataAfter = self::$sdk->getNftApi()->getDataWithClass(new ChainObject(self::$nftIssuedId), NftApple::class);
        /** @var NftApple $dataAfter */
        $dataAfter = $nftDataAfter->getData();
        self::assertEquals($eaten, !$dataAfter->getEaten()->getValue());
    }

    public static function testUpdateDataRaw(): void
    {
        $nftData = self::$sdk->getNftApi()->getDataWithClass(new ChainObject(self::$nftIssuedId), NftApple::class);
        /** @var NftApple $data */
        $data = $nftData->getData();
        $eaten = $data->getEaten()->getValue();
        $data = [new Variant('boolean', !$eaten, $data->getEaten()->getName())];

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getNftApi()->updateDataRaw($credentials, $nftData->getId(), $data);

        $nftDataAfter = self::$sdk->getNftApi()->getDataWithClass(new ChainObject(self::$nftIssuedId), NftApple::class);
        /** @var NftApple $dataAfter */
        $dataAfter = $nftDataAfter->getData();
        self::assertEquals($eaten, !$dataAfter->getEaten()->getValue());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetAll(): void
    {
        $nfts = self::$sdk->getNftApi()->getAll([self::$nftId]);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals(self::$symbol, $nft->getSymbol());
        $this->assertEquals(100, $nft->getOptions()->getMaxSupply());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetById(): void
    {
        $nft = self::$sdk->getNftApi()->getById(self::$nftId);
        $this->assertEquals(self::$symbol, $nft->getSymbol());
        $this->assertEquals(100, $nft->getOptions()->getMaxSupply());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     */
    public function testGetByIdFail(): void
    {
        $this->expectException(ObjectNotFoundException::class);
        self::$sdk->getNftApi()->getById(new ChainObject('1.10.100000'));
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetBySymbol(): void
    {
        $nft = self::$sdk->getNftApi()->getBySymbol(self::$symbol);
        $this->assertEquals(self::$symbol, $nft->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllBySymbol(): void
    {
        $nfts = self::$sdk->getNftApi()->getAllBySymbol([self::$symbol, 'APPLE.NESTED']);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals(self::$symbol, $nft->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllData(): void
    {
        self::$sdk->registerNfts([self::$nftId->getId() => NftApple::class]);
        $nfts = self::$sdk->getNftApi()->getAllData([new ChainObject(self::$nftIssuedId)]);
        /** @var NftData $nft */
        foreach ($nfts as $nft) {
            $this->assertInstanceOf(NftApple::class, $nft->getData());
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllDataRaw(): void
    {
        $rawData = self::$sdk->getNftApi()->getAllDataRaw([new ChainObject(self::$nftIssuedId)]);
        foreach ($rawData as $data) {
            $this->assertInstanceOf(NftData::class, $data);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testGetData(): void
    {
        $data = self::$sdk->getNftApi()->getDataWithClass(new ChainObject(self::$nftIssuedId), NftApple::class);
        $this->assertInstanceOf(NftApple::class, $data->getData());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testGetDataRegistered(): void
    {
        self::$sdk->registerNfts([self::$nftId->getId() => NftApple::class]);
        $data = self::$sdk->getNftApi()->getData(new ChainObject(self::$nftIssuedId));
        $this->assertInstanceOf(NftApple::class, $data->getData());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testGetDataRaw(): void
    {
        $data = self::$sdk->getNftApi()->getDataRaw(new ChainObject(self::$nftIssuedId));
        $this->assertNotNull($data);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testCountAll(): void
    {
        $count = self::$sdk->getNftApi()->countAll();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testCountAllData(): void
    {
        $count = self::$sdk->getNftApi()->countAllData();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetNftBalancesRaw(): void
    {
        $balances = self::$sdk->getNftApi()->getNftBalancesRaw(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2));
        foreach ($balances as $balance) {
            $this->assertInstanceOf(NftData::class, $balance);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetNftBalances(): void
    {
        self::$sdk->registerNfts([self::$nftId->getId() => NftApple::class]);
        $balances = self::$sdk->getNftApi()->getNftBalances(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), [self::$nftId]);
        /** @var NftData $balance */
        $balance = reset($balances);
        $this->assertInstanceOf(NftApple::class, $balance->getData());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetNftBalancesWithClass(): void
    {
        $balances = self::$sdk->getNftApi()->getNftBalancesWithClass(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), self::$nftId, NftApple::class);
        /** @var NftData $balance */
        $balance = reset($balances);
        $this->assertInstanceOf(NftApple::class, $balance->getData());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListAllRelative(): void
    {
        $nfts = self::$sdk->getNftApi()->listAllRelative();

        $this->assertGreaterThan(0, count($nfts));

        foreach ($nfts as $nft) {
            $this->assertInstanceOf(Nft::class, $nft);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListDataByNftRaw(): void
    {
        $nfts = self::$sdk->getNftApi()->listDataByNftRaw(self::$nftId);
        foreach ($nfts as $nft) {
            $this->assertInstanceOf(NftData::class, $nft);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListDataByNft(): void
    {
        self::$sdk->registerNfts([self::$nftId->getId() => NftApple::class]);
        $nfts = self::$sdk->getNftApi()->listDataByNft(self::$nftId);
        /** @var NftData $nft */
        foreach ($nfts as $nft) {
            $this->assertInstanceOf(NftApple::class, $nft->getData());
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListDataByNftWIthClass(): void
    {
        $nfts = self::$sdk->getNftApi()->listDataByNftWithClass(self::$nftId, NftApple::class);
        /** @var NftData $nft */
        foreach ($nfts as $nft) {
            $this->assertInstanceOf(NftApple::class, $nft->getData());
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testSearchNftHistory(): void
    {
        $history = self::$sdk->getNftApi()->searchNftHistory(new ChainObject(self::$nftIssuedId));
        /** @var TransactionDetail $item */
        foreach ($history as $item) {
            $this->assertInstanceOf(TransactionDetail::class, $item);
            $this->assertEquals(self::$nftIssuedId, $item->getNftDataId()->getId());
        }
    }
}