<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\RegionalPrice;
use DCorePHPTests\DCoreSDKTest;

class ContentApiTest extends DCoreSDKTest
{
    /** @var string */
    private $contentUri;
    /** @var ChainObject */
    private $contentId;

    public function setUp()
    {
        parent::setUp();

        $this->contentUri = 'http://decent.ch?testtime=' . time();

        $content = new SubmitContent();
        $content
            ->setUri($this->contentUri)
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
        $this->sdk->getContentApi()->create($content, $credentials, (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'));

        $submittedContentObject = $this->sdk->getContentApi()->getByURI($this->contentUri);
        $this->contentId = $submittedContentObject->getId();
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testGenerateKeys(): void
    {
        $contentKeys = $this->sdk->getContentApi()->generateKeys([]);

        $this->assertInstanceOf(ContentKeys::class, $contentKeys);
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testGet(): void
    {
        $contentByURI = $this->sdk->getContentApi()->getByURI($this->contentUri);

        /** @var ContentObject $content */
        $content = $this->sdk->getContentApi()->get($contentByURI->getId());

        $this->assertEquals($this->contentUri, $content->getURI());
        $this->assertEquals('1.2.27', $content->getAuthor());
        $this->assertEquals('2222222222222222222222222222222222222222', $content->getHash());
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testGetByURI(): void
    {
        $content = $this->sdk->getContentApi()->getByURI($this->contentUri);

        $this->assertEquals($this->contentUri, $content->getURI());
        $this->assertEquals('1.2.27', $content->getAuthor());
        $this->assertEquals('2222222222222222222222222222222222222222', $content->getHash());
    }

    // TODO: Untested no data
    public function testListAllPublishersRelative(): void
    {
        $sth = $this->sdk->getContentApi()->listAllPublishersRelative('');
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testRestoreEncryptionKey(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $response = $this->sdk->getContentApi()->restoreEncryptionKey(
//            (new PubKey())->setPubKey('8149734503494312909116126763927194608124629667940168421251424974828815164868905638030541425377704620941193711130535974967507480114755414928915429397074890'),
//            new ChainObject('2.12.3')
//        );
//
//        $this->assertEquals('0000000000000000000000000000000000000000000000000000000000000000', $response);
    }

    public function testFindAll(): void
    {
        $contents = $this->sdk->getContentApi()->findAll();

        $this->assertInternalType('array', $contents);

        foreach ($contents as $content) {
            $this->assertInstanceOf(Content::class, $content);
        }
    }

    /**
     * @throws \Exception
     */
    public function testCreatePurchaseOperation(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $purchaseOp = $this->sdk->getContentApi()->createPurchaseOperation($credentials, clone $this->contentId);

        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $purchaseOp->getConsumer());
        $this->assertEquals($this->contentUri, $purchaseOp->getUri());
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testPurchase(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $this->sdk->getContentApi()->purchase($credentials, $this->contentId);

        $contentAfter = $this->sdk->getContentApi()->getByURI($this->contentUri);
        $this->assertEquals(1, $contentAfter->getTimesBought());
    }

    public function testPurchaseWithUri(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws \Exception
     */
    public function testCreate(): void
    {
        $randomUri = 'http://decent.ch?testtime=' . time();

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
        $this->sdk->getContentApi()->create($content, $credentials, (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'));

        $submittedContentObject = $this->sdk->getContentApi()->getByURI($randomUri);
        $this->assertEquals( $randomUri, $submittedContentObject->getURI());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $submittedContentObject->getAuthor());
        $this->assertEquals('2222222222222222222222222222222222222222', $submittedContentObject->getHash());
    }

    /**
     * @throws \DCorePHP\Exception\ObjectAlreadyFoundException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws ValidationException
     * @throws \Exception
     */
    public function testUpdate(): void
    {
        $uri = 'http://decent.ch?PHPtesttime=' . time();
        $expiration = new \DateTime('+2 day');

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $content = new SubmitContent();
        $content
            ->setUri($uri)
            ->setCoauthors([])
            ->setCustodyData(null)
            ->setHash('2222222222222222222222222222222222222222')
            ->setKeyParts([])
            ->setSeeders([])
            ->setQuorum(0)
            ->setSize(10000)
            ->setSynopsis(json_encode(['title' => 'Game Title', 'description' => 'Description', 'content_type_id' => '1.2.3']))
            ->setExpiration($expiration)
            ->setPrice([(new RegionalPrice)->setPrice((new AssetAmount())->setAmount(1000))->setRegion(1)]);

        $this->sdk->getContentApi()->create($content, $credentials, (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1000000)->setAssetId('1.3.0'));

        $content->setSynopsis(json_encode(['title' => 'Game Title Updated by PHP', 'description' => 'Description Updated by PHP', 'content_type_id' => '1.2.3']));
        $this->sdk->getContentApi()->update($content, $credentials, (new AssetAmount())->setAmount(1000001)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1000001)->setAssetId('1.3.0'));

        $submittedContentObject = $this->sdk->getContentApi()->getByURI($uri);
        $this->assertEquals( $uri, $submittedContentObject->getURI());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $submittedContentObject->getAuthor());
        $this->assertEquals('Game Title Updated by PHP', $submittedContentObject->getSynopsisDecoded()['title']);
    }

    /**
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \Exception
     */
    public function testDeleteByUrl(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $randomUri = 'http://decent.ch?testtime=' . time() . '&lang=PHP';
//        $credentials = new Credentials(new ChainObject('1.2.34'), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
//
//        $content = new SubmitContent();
//        $content
//            ->setUri($randomUri)
//            ->setCoauthors([])
//            ->setCustodyData(null)
//            ->setHash('2222222222222222222222222222222222222222')
//            ->setKeyParts([])
//            ->setSeeders([])
//            ->setQuorum(0)
//            ->setSize(10000)
//            ->setSynopsis(json_encode(['title' => 'Game Title', 'description' => 'Description', 'content_type_id' => '1.2.3']))
//            ->setExpiration(new \DateTime('+2 day'))
//            ->setPrice([(new RegionalPrice)->setPrice((new AssetAmount())->setAmount(1000))->setRegion(1)]);
//
//        $this->sdk->getContentApi()->create($content, $credentials, (new AssetAmount())->setAmount(0)->setAssetId('1.3.0'), (new AssetAmount())->setAmount(1000)->setAssetId('1.3.0'));
//
//        dump('Before:');
//        dump($this->sdk->getContentApi()->getByURI($randomUri));
//
//        $this->sdk->getContentApi()->deleteByUrl($randomUri, $credentials, (new AssetAmount())->setAmount(0)->setAssetId('1.3.0'));
//
//        dump('After:');
//        dump($this->sdk->getContentApi()->getByURI($randomUri));
    }

    public function testSubmitContentAsync(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testContentCancellation(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testDownloadContent(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetDownloadStatus(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testLeaveRatingAndComment(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGenerateEncryptionKey(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSearchUserContent(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAuthorAndCoAuthorsByUri(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}
