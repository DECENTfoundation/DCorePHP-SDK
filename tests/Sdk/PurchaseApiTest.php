<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Purchase;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\RegionalPrice;
use DCorePHPTests\DCoreSDKTest;

class PurchaseApiTest extends DCoreSDKTest
{

    public function testGetAllHistory(): void
    {
        $purchases = $this->sdk->getPurchaseApi()->getAllHistory(new ChainObject('1.2.34'));

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }

        $this->assertInternalType('array', $purchases);
    }

    public function testGetAllOpen(): void
    {
        $purchases = $this->sdk->getPurchaseApi()->getAllOpen();

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }

        $this->assertInternalType('array', $purchases);
    }

    public function testGetAllOpenByUri(): void
    {

        $purchases = $this->sdk->getPurchaseApi()->getAllOpenByUri('http://some.uri');

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }

        $this->assertInternalType('array', $purchases);
    }

    public function testGetAllOpenByAccount(): void
    {
        $purchases = $this->sdk->getPurchaseApi()->getAllOpenByAccount(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }

        $this->assertInternalType('array', $purchases);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGet(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $purchase = $this->sdk->getPurchaseApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), 'ipfs:QmWBoRBYuxzH5a8d3gssRbMS5scs6fqLKgapBfqVNUFUtZ');
//
//        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $purchase->getConsumer()->getId());
//        $this->assertEquals('ipfs:QmWBoRBYuxzH5a8d3gssRbMS5scs6fqLKgapBfqVNUFUtZ', $purchase->getUri());
    }

    public function testFindAll(): void
    {
        $purchases = $this->sdk->getPurchaseApi()->findAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), 'new');

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }

        $this->assertInternalType('array', $purchases);
    }

    public function testFindAllForFeedback(): void
    {
        $purchases = $this->sdk->getPurchaseApi()->findAllForFeedback('');

        $this->assertInternalType('array', $purchases);
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     * @throws \Exception
     */
    public function testRateAndComment(): void
    {
        $randomUri = 'http://decent.ch?PHP&testtime=' . time();
        $content = new SubmitContent();
        $content
            ->setUri($randomUri)
            ->setCoauthors([])
            ->setCustodyData(null)
            ->setHash('2222222222222222222222222222222222222222')
            ->setKeyParts([])
            ->setSeeders([])
            ->setQuorum(0)
            ->setSize(10000)
            ->setSynopsis(json_encode(['title' => 'Game Title', 'description' => 'Description', 'content_type_id' => '1.2.3']))
            ->setExpiration((new \DateTime())->modify('+1 month'))
            ->setPrice([(new RegionalPrice)->setPrice((new AssetAmount())->setAmount(1000))->setRegion(1)]);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $this->sdk->getContentApi()->create(
            $content,
            $credentials,
            (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'),
            (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'));

        $content = $this->sdk->getContentApi()->getByURI($randomUri);

        $this->sdk->getContentApi()->purchase($credentials, $content->getId());

        $this->sdk->getPurchaseApi()->rateAndComment($credentials, $content->getURI(), 5, 'PHP Rating Comment');

        $contentAfter = $this->sdk->getContentApi()->getByURI($randomUri);
        $this->assertEquals(5000, $contentAfter->getAVGRating());
    }

}
