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
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use WebSocket\BadOpcodeException;

class NftApiTest extends DCoreSDKTest
{
    /** @var ChainObject */
    private $testNftId;
    /** @var string */
    private $nftSymbol;

    public function setUp()
    {
        parent::setUp();

        $this->nftSymbol = 'APPLE' . time() . 'T';

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->create($credentials, $this->nftSymbol, 100, false, 'an apple', new NftApple(), true);

        $nft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        $this->testNftId = $nft->getId();

        sleep(5);
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGetAll(): void
    {
        $nfts = $this->sdk->getNftApi()->getAll([new ChainObject($this->testNftId)]);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals($this->nftSymbol, $nft->getSymbol());
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     */
    public function testGetById(): void
    {
        $nft = $this->sdk->getNftApi()->getById(new ChainObject($this->testNftId));
        $this->assertEquals($this->nftSymbol, $nft->getSymbol());
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
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
        $this->sdk->getNftApi()->getById(new ChainObject('1.10.100000'));
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetBySymbol(): void
    {
        $nft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllBySymbol(): void
    {
        $nfts = $this->sdk->getNftApi()->getAllBySymbol([$this->nftSymbol, 'APPLE.NESTED']);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals($this->nftSymbol, $nft->getSymbol());
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllData(): void
    {
        // TODO: Ids ?
        $this->sdk->registerNfts(['1.10.46' => NftApple::class, '1.10.50' => NftApple::class]);
        $nfts = $this->sdk->getNftApi()->getAllData([new ChainObject('1.11.0'), new ChainObject('1.11.1')]);
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
        $rawData = $this->sdk->getNftApi()->getAllDataRaw([new ChainObject('1.11.0')]);
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
        $data = $this->sdk->getNftApi()->getDataWithClass(new ChainObject('1.11.0'), NftApple::class);
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
        // TODO: Id => 46 ? should be 0
        $this->sdk->registerNfts(['1.10.46' => NftApple::class]);
        $data = $this->sdk->getNftApi()->getData(new ChainObject('1.11.0'));
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
        $data = $this->sdk->getNftApi()->getDataRaw(new ChainObject('1.11.0'));
        $this->assertNotNull($data);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testCountAll(): void
    {
        $count = $this->sdk->getNftApi()->countAll();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testCountAllData(): void
    {
        $count = $this->sdk->getNftApi()->countAllData();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetNftBalancesRaw(): void
    {
        $balances = $this->sdk->getNftApi()->getNftBalancesRaw(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
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
        // TODO: Id => 46 ? should be 0
        $this->sdk->registerNfts(['1.10.46' => NftApple::class]);
        $balances = $this->sdk->getNftApi()->getNftBalances(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), [new ChainObject('1.10.46')]);
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
        // TODO: Id => 46 ? should be 0
        $balances = $this->sdk->getNftApi()->getNftBalancesWithClass(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.10.46'), NftApple::class);
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
        $nfts = $this->sdk->getNftApi()->listAllRelative();

        $this->assertGreaterThan(0, count($nfts));

        foreach ($nfts as $nft) {
            $this->assertInstanceOf(Nft::class, $nft);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testListDataByNftRaw(): void
    {
        // TODO: Id => 46 ? should be 0
        $nfts = $this->sdk->getNftApi()->listDataByNftRaw(new ChainObject('1.10.46'));
        foreach ($nfts as $nft) {
            $this->assertInstanceOf(NftData::class, $nft);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testListDataByNft(): void
    {
        $this->sdk->registerNfts(['1.10.46' => NftApple::class]);
        // TODO: Id => 46 ? should be 0
        $nfts = $this->sdk->getNftApi()->listDataByNft(new ChainObject('1.10.46'));
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
    public function testListDataByNftWIthClass(): void
    {
        // TODO: Id => 46 ? should be 0
        $nfts = $this->sdk->getNftApi()->listDataByNftWithClass(new ChainObject('1.10.46'), NftApple::class);
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
        $history = $this->sdk->getNftApi()->searchNftHistory(new ChainObject('1.11.2'));
        /** @var TransactionDetail $item */
        foreach ($history as $item) {
            $this->assertInstanceOf(TransactionDetail::class, $item);
            $this->assertEquals('1.11.2', $item->getNftDataId()->getId());
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testCreate(): void
    {
        $symbol = 'APPLE' . time() . 'T';
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->create($credentials, $symbol, 100, false, 'an apple', new NftApple(), true);

        $nft = $this->sdk->getNftApi()->getBySymbol($symbol);

        $this->assertNotNull($nft);
        $this->assertEquals(100, $nft->getOptions()->getMaxSupply());
        $this->assertFalse($nft->getOptions()->isFixedMaxSupply());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->update($credentials, $this->nftSymbol, null, null, 'an apple update');

        $updatedNft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        $this->assertEquals('an apple update', $updatedNft->getOptions()->getDescription());
    }

    /**
     * @throws BadOpcodeException
     * @throws ExceptionInterface
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testIssue(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $apple = new NftApple(5, 'red', false);
        $this->sdk->getNftApi()->issue($credentials, $this->nftSymbol, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), $apple);

        $issuedNft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        $this->assertEquals(1, $issuedNft->getCurrentSupply());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testTransfer(): void
    {
//        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->transfer($credentials, new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), new ChainObject('1.11.0'));

        $issuedNft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        dump($issuedNft);
        $this->assertEquals(1, $issuedNft->getCurrentSupply());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdateData(): void
    {
        $nftData = $this->sdk->getNftApi()->getDataWithClass(new ChainObject('1.11.0'), NftApple::class);
        /** @var NftApple $data */
        $data = $nftData->getData();
        $eaten = $data->getEaten()->getValue();
        $data->getEaten()->setValue(!$data->getEaten()->getValue());

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->updateData($credentials, $nftData->getId(), $data);

        $nftDataAfter = $this->sdk->getNftApi()->getDataWithClass(new ChainObject('1.11.0'), NftApple::class);
        /** @var NftApple $dataAfter */
        $dataAfter = $nftDataAfter->getData();
        $this->assertEquals($eaten, !$dataAfter->getEaten()->getValue());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdateDataRaw(): void
    {
        $nftData = $this->sdk->getNftApi()->getDataWithClass(new ChainObject('1.11.0'), NftApple::class);
        /** @var NftApple $data */
        $data = $nftData->getData();
        $eaten = $data->getEaten()->getValue();
        $data = [new Variant('boolean', !$eaten, $data->getEaten()->getName())];

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->updateDataRaw($credentials, $nftData->getId(), $data);

        $nftDataAfter = $this->sdk->getNftApi()->getDataWithClass(new ChainObject('1.11.0'), NftApple::class);
        /** @var NftApple $dataAfter */
        $dataAfter = $nftDataAfter->getData();
        $this->assertEquals($eaten, !$dataAfter->getEaten()->getValue());
    }
}