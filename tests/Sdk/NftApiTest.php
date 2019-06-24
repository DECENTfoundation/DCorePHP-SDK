<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Nft;
use DCorePHP\Model\NftDataType;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class NftApiTest extends DCoreSDKTest
{
    /** @var ChainObject */
    private $testNftId;
    /** @var string */
    private $symbol;
    /** @var array */
    private $nftApple;

    public function setUp()
    {
        parent::setUp();

        $this->symbol = 'APPLE';
        $this->nftApple = [
            NftDataType::withValues('integer', 'size'),
            NftDataType::withValues('string', 'color', true),
            NftDataType::withValues('boolean', 'eaten', false, NftDataType::BOTH),
        ];

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->create($credentials, $this->symbol, 100, false, 'an apple', $this->nftApple, true);

        $nft = $this->sdk->getNftApi()->getBySymbol($this->symbol);
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
        $nfts = $this->sdk->getNftApi()->getAll([new ChainObject('1.10.0'), new ChainObject('1.10.1')]);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals('APPLE', $nft->getSymbol());
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
        $nft = $this->sdk->getNftApi()->getById(new ChainObject('1.10.0'));
        $this->assertEquals('APPLE', $nft->getSymbol());
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
        $nft = $this->sdk->getNftApi()->getBySymbol('APPLE');
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllBySymbol(): void
    {
        $nfts = $this->sdk->getNftApi()->getAllBySymbol(['APPLE', 'APPLE.NESTED']);
        /** @var Nft $nft */
        $nft = reset($nfts);
        $this->assertEquals('APPLE', $nft->getSymbol());
        $this->assertEquals('an apple', $nft->getOptions()->getDescription());
    }

    public function testGetAllData(): void
    {
//        $nfts = $this->sdk->getNftApi()->getAllData([new ChainObject('1.11.0'), new ChainObject('1.11.1')]);
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testCreate(): void
    {
        $nftModel = [
            NftDataType::withValues('integer', 'size'),
            NftDataType::withValues('string', 'color', true),
            NftDataType::withValues('boolean', 'eaten', false, NftDataType::BOTH),
        ];
        $symbol = 'APPLE' . time() . 'T';
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getNftApi()->create($credentials, $symbol, 100, false, 'an apple', $nftModel, true);

        $nft = $this->sdk->getNftApi()->getBySymbol($symbol);

        $this->assertNotNull($nft);
        $this->assertEquals(100, $nft->getOptions()->getMaxSupply());
        $this->assertFalse($nft->getOptions()->isFixedMaxSupply());
    }
}