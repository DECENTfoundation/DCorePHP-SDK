<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\RegionalPrice;
use DCorePHP\Utils\Math;

class ContentSubmitOperation extends BaseOperation
{
    public const OPERATION_TYPE = 20;
    public const OPERATION_NAME = 'content_submit';

    /** @var SubmitContent */
    private $content;
    /** @var ChainObject */
    private $author;
    /** @var AssetAmount */
    private $publishingFee;

    public function __construct()
    {
        parent::__construct();
        $this->content = new SubmitContent();
        $this->publishingFee = new AssetAmount();
    }

    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[size]' => 'content.size',
                '[author]' => 'author',
                //TODO: Hydrate co_authors [Unknown structure]
//                '[co_authors]' => 'content.coAuthors',
                '[URI]' => 'content.uri',
                '[quorum]' => 'content.quorum',
                '[hash]' => 'content.hash',
                //TODO: Hydrate seeders [Unknown structure]
//                '[seeders]' => 'content.seeders',
                //TODO: Hydrate key_parts [Unknown structure]
//                '[key_parts]' => 'content.keyParts',
                '[expiration]' => 'content.expiration',
                '[publishing_fee][amount]' => 'publishingFee.amount',
                '[publishing_fee][asset_id]' => 'publishingFee.asset_id',
                '[synopsis]' => 'content.synopsis'
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                // skip
            }
        }

        $rawPrices = $rawOperation['price'];

        $regionalPrices = [];
        foreach ($rawPrices as $rawPrice) {
            $regionalPrice = new RegionalPrice();
            foreach (
                [
                    '[region]' => 'region',
                    '[price][amount]' => 'price.amount',
                    '[price][asset_id]' => 'price.assetId',
                ] as $path => $modelPath
            ) {
                try {
                    $value = $this->getPropertyAccessor()->getValue($rawPrice, $path);
                    $this->getPropertyAccessor()->setValue($regionalPrice, $modelPath, $value);
                } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                    // skip
                }
            }
            $regionalPrices[] = $regionalPrice;
        }
        $this->getContent()->setPrice($regionalPrices);
    }

    /**
     * @return SubmitContent
     */
    public function getContent(): SubmitContent
    {
        return $this->content;
    }

    /**
     * @param SubmitContent $content
     * @return ContentSubmitOperation
     */
    public function setContent(SubmitContent $content): ContentSubmitOperation
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAuthor(): ChainObject
    {
        return $this->author;
    }

    /**
     * @param ChainObject|string $author
     * @return ContentSubmitOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAuthor($author): ContentSubmitOperation
    {
        if (is_string($author)) {
            $author = new ChainObject($author);
        }
        $this->author = $author;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPublishingFee(): AssetAmount
    {
        return $this->publishingFee;
    }

    /**
     * @param AssetAmount $publishingFee
     * @return ContentSubmitOperation
     */
    public function setPublishingFee(AssetAmount $publishingFee): ContentSubmitOperation
    {
        $this->publishingFee = $publishingFee;
        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'size' => $this->getContent()->getSize(),
                'author' => $this->getAuthor()->getId(),
                'co_authors' => $this->getContent()->getCoauthors(),
                'URI' => $this->getContent()->getUri(),
                'quorum' => $this->getContent()->getQuorum(),
                'price' => array_map(
                    function (RegionalPrice $regionalPrice) {
                        return $regionalPrice->toArray();
                    }, $this->getContent()->getPrice()
                ),
                'hash' => $this->getContent()->getHash(),
                'seeders' => $this->getContent()->getSeeders(),
                'key_parts' => array_map(
                    function (KeyParts $keyParts) {
                        return $keyParts->toArray();
                    }, $this->getContent()->getKeyParts()
                ),
                'expiration' => $this->getContent()->getExpiration()->format('Y-m-d\TH:i:s'),
                'publishing_fee' => $this->getPublishingFee()->toArray(),
                'synopsis' => $this->getContent()->getSynopsis(),
                'fee' => $this->getFee()->toArray()
            ],
        ];
    }

    public function toBytes(): string
    {
        return
            implode('',
            [
                $this->getTypeBytes(),
                $this->getFee()->toBytes(),
                str_pad(Math::gmpDecHex(Math::reverseBytesLong($this->getContent()->getSize())), 16, '0', STR_PAD_LEFT),
                $this->getAuthor()->toBytes(),
                '00',
                Math::gmpDecHex(strlen(unpack('H*', $this->getContent()->getUri())[1]) / 2).unpack('H*', $this->getContent()->getUri())[1],
                str_pad(Math::gmpDecHex(Math::reverseBytesLong($this->getContent()->getQuorum())), 8, '0', STR_PAD_LEFT),
                // TODO: Hardcoded string '01' as a part of price bytes()
                '01',
                $this->getContent()->getPrice() ? implode('', array_map(function (RegionalPrice $regionalPrice) { // operation bytes
                        return $regionalPrice->toBytes();
                    }, $this->getContent()->getPrice())) : '00',
                $this->getContent()->getHash(),
                $this->getContent()->getSeeders() ? implode('', array_map(function (ChainObject $seeder) { // operation bytes
                        return $seeder->toBytes();
                    }, $this->getContent()->getPrice())) : '00',
                '00', // TODO: Hardcoded KeyParts
                implode('', array_reverse(str_split(str_pad(Math::gmpDecHex($this->getContent()->getExpiration()->format('U')), 8, '0', STR_PAD_LEFT), 2))),
                $this->getPublishingFee()->toBytes(),
                Math::gmpDecHex(strlen(unpack('H*', $this->getContent()->getSynopsis())[1]) / 2).unpack('H*', $this->getContent()->getSynopsis())[1],
                $this->getContent()->getCustodyData() ? $this->getContent()->getCustodyData()->toBytes() : '00'
            ]
        );
    }
}
