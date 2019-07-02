<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Nft;
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

    public function testGetAllData(): void
    {
//        $nfts = $this->sdk->getNftApi()->getAllData([new ChainObject('1.11.0'), new ChainObject('1.11.1')]);
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllDataRaw(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $this->sdk->getNftApi()->getAllDataRaw([new ChainObject('1.11.0')]);
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
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $count = $this->sdk->getNftApi()->countAllData();
//        $this->assertGreaterThan(0, $count);
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

    public function testIssue(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $apple = new NftApple(5, 'red', false);
        $this->sdk->getNftApi()->issue($credentials, $this->nftSymbol, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), $apple);

        $issuedNft = $this->sdk->getNftApi()->getBySymbol($this->nftSymbol);
        $this->assertEquals(1, $issuedNft->getCurrentSupply());
    }
}