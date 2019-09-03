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
use DCorePHP\Model\PubKey;
use DCorePHP\Model\RegionalPrice;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class ContentApiTest extends DCoreSDKTest
{
    /** @var string */
    private static $contentUri1;
    /** @var ChainObject */
    private static $contentId1;
    /** @var string */
    private static $contentUri2;
    /** @var ChainObject */
    private static $contentId2;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::testAdd();
        self::testAddWithCoauthors();
//        self::testPurchase();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public static function testAddWithCoauthors(): void
    {
        self::$contentUri1 = 'http://decent.ch?testtime=' . time();
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $coAuthors = (new CoAuthors())->setAuthors([[new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), 1000]]);
        $price = (new RegionalPrice())->setPrice((new AssetAmount())->setAmount(2));
        $expiration = (new DateTime())->modify('+100 days');
        $synopsis = json_encode(['title' => 'hello', 'description' => 'world', 'content_type_id' => '0.0.0']);

        self::$sdk->getContentApi()->add($credentials, $coAuthors, self::$contentUri1, [$price], $expiration, $synopsis);

        $content = self::$sdk->getContentApi()->getByURI(self::$contentUri1);
        self::$contentId1 = $content->getId();

        self::assertNotNull($content);
        self::assertEquals(self::$contentUri1, $content->getURI());
    }

    /**
     * @throws Exception
     */
    public static function testAdd(): void
    {
        self::$contentUri2 = 'http://decent.ch?testtime=' . time();
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $price = (new RegionalPrice())->setPrice((new AssetAmount())->setAmount(20));
        $synopsis = json_encode(['title' => 'hello', 'description' => 'world', 'content_type_id' => '0.0.0']);
        $expiration = (new DateTime())->modify('+10 days');

        self::$sdk->getContentApi()->add($credentials, new CoAuthors(), self::$contentUri2, [$price], $expiration, $synopsis);

        $content = self::$sdk->getContentApi()->getByURI(self::$contentUri2);
        self::$contentId2 = $content->getId();

        self::assertNotNull($content);
        self::assertEquals(self::$contentUri2, $content->getURI());
    }

    /**
     * @throws ValidationException
     * @doesNotPerformAssertions
     */
    public function testTransfer(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getContentApi()->transfer($credentials, self::$contentId1, (new AssetAmount())->setAmount(1),
            'transfer to content');
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
        self::$sdk->getContentApi()->updateWithUri($credentials, self::$contentUri1, json_encode(['title' => 'hello', 'description' => 'update', 'content_type_id' => '0.0.0']));

        $content = self::$sdk->getContentApi()->getByURI(self::$contentUri1);
        self::$contentId2 = $content->getId();
        $contentJson = json_decode($content->getSynopsis(), true);

        self::assertNotNull($content);
        self::assertEquals($contentJson['description'], 'update');
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public static function testPurchase(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        self::$sdk->getContentApi()->purchase($credentials, self::$contentId1);

        $contentAfter = self::$sdk->getContentApi()->get(self::$contentId1);
        self::assertEquals(1, $contentAfter->getTimesBought());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testPurchaseWithUri(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        self::$sdk->getContentApi()->purchaseWithUri($credentials, self::$contentUri2);

        $contentAfter = self::$sdk->getContentApi()->get(self::$contentId2);
        self::assertEquals(1, $contentAfter->getTimesBought());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testRemove(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        self::$sdk->getContentApi()->removeById($credentials, self::$contentId2);

        $content = self::$sdk->getContentApi()->get(self::$contentId2);

        self::assertNotNull($content);
        self::assertTrue($content->isBlocked());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @doesNotPerformAssertions
     */
    public function testGenerateKeys(): void
    {
        self::$sdk->getContentApi()->generateKeys([]);
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws BadOpcodeException
     */
    public function testGet(): void
    {
        $content = self::$sdk->getContentApi()->get(self::$contentId1);

        $this->assertEquals(self::$contentUri1, $content->getURI());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $content->getAuthor());
        $this->assertEquals('1', $content->getSize());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAll(): void
    {
        $contents = self::$sdk->getContentApi()->getAll([self::$contentId1]);
        $content = reset($contents);

        $this->assertEquals(self::$contentUri1, $content->getURI());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $content->getAuthor());
        $this->assertEquals('1', $content->getSize());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws BadOpcodeException
     */
    public function testGetByURI(): void
    {
        $content = self::$sdk->getContentApi()->getByURI(self::$contentUri1);

        $this->assertEquals(self::$contentUri1, $content->getURI());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $content->getAuthor());
        $this->assertEquals('1', $content->getSize());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @doesNotPerformAssertions
     */
    public function testListAllPublishersRelative(): void
    {
        self::$sdk->getContentApi()->listAllPublishersRelative('');
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testRestoreEncryptionKey(): void
    {
        $keyPair = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1);
        $pub = (new PubKey())->setPubKey($keyPair->getPrivate()->toElGamalPrivateKey());
        $key = self::$sdk->getContentApi()->restoreEncryptionKey($pub, new ChainObject('2.12.0'));

        self::assertEquals('0000000000000000000000000000000000000000000000000000000000000000', $key);
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @doesNotPerformAssertions
     */
    public function testFindAll(): void
    {
        self::$sdk->getContentApi()->findAll('');
    }
}
