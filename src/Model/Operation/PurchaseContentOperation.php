<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\PubKey;
use DCorePHP\Utils\Math;

class PurchaseContentOperation extends BaseOperation
{
    public const OPERATION_TYPE = 21;
    public const OPERATION_NAME = 'request_to_buy';

    /** @var string */
    private $uri;
    /** @var ChainObject */
    private $consumer;
    /** @var AssetAmount */
    private $price;
    /** @var PubKey */
    private $publicElGamal;
    /** @var integer */
    private $regionCode;

    /**
     * PurchaseContentOperationTest constructor.
     * @param Credentials $credentials
     * @param ContentObject $content
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function __construct(Credentials $credentials, ContentObject $content)
    {
        parent::__construct();
        $this
            ->setUri($content->getURI())
            ->setConsumer($credentials->getAccount())
            ->setPrice($content->getPriceNone())
            ->setPublicElGamal(parse_url($content->getURI(), PHP_URL_SCHEME) !== 'ipfs' ? new PubKey() : (new PubKey())->setPubKey($credentials->getKeyPair()->getPrivate()->toElGamalPublicKey()))
            ->setRegionCode(ContentObject::REGIONS_NONE_ID);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return PurchaseContentOperation
     */
    public function setUri(string $uri): PurchaseContentOperation
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject|string $consumer
     * @return PurchaseContentOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): PurchaseContentOperation
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPrice(): AssetAmount
    {
        return $this->price;
    }

    /**
     * @param AssetAmount $price
     * @return PurchaseContentOperation
     */
    public function setPrice(AssetAmount $price): PurchaseContentOperation
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return PubKey
     */
    public function getPublicElGamal(): PubKey
    {
        return $this->publicElGamal;
    }

    /**
     * @param PubKey $publicElGamal
     * @return PurchaseContentOperation
     */
    public function setPublicElGamal(PubKey $publicElGamal): PurchaseContentOperation
    {
        $this->publicElGamal = $publicElGamal;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegionCode(): int
    {
        return $this->regionCode;
    }

    /**
     * @param int $regionCode
     * @return PurchaseContentOperation
     */
    public function setRegionCode(int $regionCode): PurchaseContentOperation
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    public function toArray(): array
    {
        // TODO: Missing test in kotlin
        return parent::toArray();
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            Math::writeUnsignedVarIntHex(sizeof(Math::stringToByteArray($this->getUri()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getUri())),
            $this->getConsumer()->toBytes(),
            $this->getPrice()->toBytes(),
            str_pad(dechex(Math::reverseBytesInt($this->getRegionCode())), 8, '0', STR_PAD_LEFT),
            $this->getPublicElGamal()->toBytes()
        ]);
    }
}