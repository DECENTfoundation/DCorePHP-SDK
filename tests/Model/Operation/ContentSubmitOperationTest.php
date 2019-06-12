<?php

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\Operation\ContentSubmitOperation;
use DCorePHP\Model\RegionalPrice;
use PHPUnit\Framework\TestCase;

class ContentSubmitOperationTest extends TestCase
{

    private $response = [
        'fee' => [
            'amount' => 50000,
            'asset_id' => '1.3.0'
        ],
        'size' => 123,
        'author' => '1.2.34',
        'co_authors' => [],
        'URI' => 'https://google.com',
        'quorum' => 0,
        'price' => [
            0 => [
                'region' => 2,
                'price' => [
                    'amount' => 1,
                    'asset_id' => '1.3.0'
                ]
            ]
        ],
        'hash' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        'seeders' => [],
        'key_parts' => [],
        'expiration' => '2018-12-11T14:00:07',
        'publishing_fee' => [
            'amount' => 10,
            'asset_id' => '1.3.0'
        ],
        'synopsis' => '{"title" : "Test Title", "description" : "Test Description", "content_type_id" : "1.1.1"}'
    ];

    public function testHydrate(): void
    {
        $contentOperation = new ContentSubmitOperation();
        $contentOperation->hydrate($this->response);

        $this->assertEquals(50000, $contentOperation->getFee()->getAmount());
        $this->assertEquals('1.3.0', $contentOperation->getFee()->getAssetId()->getId());
        $this->assertEquals('123', $contentOperation->getContent()->getSize());
        $this->assertEquals('1.2.34', $contentOperation->getAuthor()->getId());
        //TODO: Implement in ContentSubmitOperation
        //$this->assertEquals([], $contentOperation->getCoAuthors());
        $this->assertEquals('https://google.com', $contentOperation->getContent()->getUri());
        $this->assertEquals(0, $contentOperation->getContent()->getQuorum());
        $this->assertEquals(2, $contentOperation->getContent()->getPrice()[0]->getRegion());
        $this->assertEquals(1, $contentOperation->getContent()->getPrice()[0]->getPrice()->getAmount());
        $this->assertEquals('1.3.0', $contentOperation->getContent()->getPrice()[0]->getPrice()->getAssetId()->getId());
        $this->assertEquals('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $contentOperation->getContent()->getHash());
        //TODO: Implement in ContentSubmitOperation
        //$this->assertEquals([], $contentOperation->getSeeders());
        //TODO: Implement in ContentSubmitOperation
        //$this->assertEquals([], $contentOperation->getKeyParts());
        $this->assertEquals(new DateTime('2018-12-11T14:00:07'), $contentOperation->getContent()->getExpiration());
        $this->assertEquals(10, $contentOperation->getPublishingFee()->getAmount());
        $this->assertEquals('1.3.0', $contentOperation->getPublishingFee()->getAssetId()->getId());
        $this->assertEquals('Test Title', $contentOperation->getContent()->getSynopsisDecoded()['title']);
        $this->assertEquals('Test Description', $contentOperation->getContent()->getSynopsisDecoded()['description']);
        $this->assertEquals('1.1.1', $contentOperation->getContent()->getSynopsisDecoded()['content_type_id']);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     * @throws Exception
     */
    public function testToBytes(): void
    {

        $content = new SubmitContent();
        $content
            ->setCoauthors([])
            ->setSize(10000)
            ->setUri('http://hello.io/world2')
            ->setQuorum(0)
            ->setPrice([(new RegionalPrice)->setPrice((new AssetAmount())->setAmount(1000))->setRegion(1)])
            ->setHash('2222222222222222222222222222222222222222')
            ->setSeeders([])
            ->setKeyParts([])
            ->setExpiration('2019-05-28T13:32:34+00:00')
            ->setSynopsis(json_encode(['title' => 'Game Title', 'description' => 'Description', 'content_type_id' => '1.2.3']))
            ->setCustodyData(null);

        $operation = new ContentSubmitOperation();
        $operation
            ->setAuthor(new ChainObject('1.2.34'))
            ->setPublishingFee((new AssetAmount())->setAssetId('1.3.0')->setAmount(1000))
            ->setContent($content);

        $this->assertEquals(
            '140000000000000000001027000000000000220016687474703a2f2f68656c6c6f2e696f2f776f726c6432000000000101000000e80300000000000000222222222222222222222222222222222222222200007238ed5ce803000000000000004c7b227469746c65223a2247616d65205469746c65222c226465736372697074696f6e223a224465736372697074696f6e222c22636f6e74656e745f747970655f6964223a22312e322e33227d00',
            $operation->toBytes()
        );
    }
}