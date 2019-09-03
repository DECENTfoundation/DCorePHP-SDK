<?php

namespace DCorePHPTests\Sdk;

use DateTime;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\Content\Purchase;
use DCorePHP\Model\RegionalPrice;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class PurchaseApiTest extends DCoreSDKTest
{
    /** @var string */
    private static $uri;
    /** @var ChainObject */
    private static $id;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::testRateAndComment();
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public static function testRateAndComment(): void
    {
        self::addAndPurchaseContent();

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getPurchaseApi()->rateAndComment($credentials, self::$uri, 5, 'PHP Rating Comment');

        $contentAfter = self::$sdk->getContentApi()->getByURI(self::$uri);
        self::assertEquals(5000, $contentAfter->getAVGRating());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGetAllHistory(): void
    {
        $purchases = self::$sdk->getPurchaseApi()->getAllHistory(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }
    }

    public function testGetAllOpen(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllOpenByUri(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllOpenByAccount(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGet(): void
    {
        $purchase = self::$sdk->getPurchaseApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), self::$uri);

        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $purchase->getConsumer()->getId());
        $this->assertEquals(self::$uri, $purchase->getUri());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testFindAll(): void
    {
        $purchases = self::$sdk->getPurchaseApi()->findAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($purchases as $purchase) {
            $this->assertInstanceOf(Purchase::class, $purchase);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testFindAllForFeedback(): void
    {
        $purchases = self::$sdk->getPurchaseApi()->findAllForFeedback(self::$uri);

        $purchase = reset($purchases);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $purchase->getConsumer()->getId());
        $this->assertEquals(self::$uri, $purchase->getUri());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    private static function addAndPurchaseContent(): void
    {
        self::$uri = 'http://decent.ch?testtime=' . time();
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $price = (new RegionalPrice())->setPrice((new AssetAmount())->setAmount(20));
        $synopsis = json_encode(['title' => 'hello', 'description' => 'world', 'content_type_id' => '0.0.0']);
        $expiration = (new DateTime())->modify('+10 days');

        self::$sdk->getContentApi()->add($credentials, new CoAuthors(), self::$uri, [$price], $expiration, $synopsis);

        $content = self::$sdk->getContentApi()->getByURI(self::$uri);
        self::$id = $content->getId();

        self::$sdk->getContentApi()->purchase($credentials, self::$id);
    }
}
