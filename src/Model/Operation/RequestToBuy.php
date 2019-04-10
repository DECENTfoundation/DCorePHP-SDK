<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\PubKey;
use DCorePHP\Utils\Math;

class RequestToBuy extends BaseOperation
{
    public const OPERATION_TYPE = 21;
    public const OPERATION_NAME = 'request_to_buy';

    /** @var ChainObject */
    private $consumer;
    /** @var string */
    private $uri;
    /** @var AssetAmount */
    private $price;
    /** @var int */
    private $regionCodeFrom;
    /** @var PubKey */
    private $elGamalPublicKey;

    public function __construct()
    {
        parent::__construct();
        $this->elGamalPublicKey = new PubKey();
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject $consumer
     * @return RequestToBuy
     */
    public function setConsumer(ChainObject $consumer): RequestToBuy
    {
        $this->consumer = $consumer;

        return $this;
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
     * @return RequestToBuy
     */
    public function setUri(string $uri): RequestToBuy
    {
        $this->uri = $uri;

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
     * @return RequestToBuy
     */
    public function setPrice(AssetAmount $price): RequestToBuy
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegionCodeFrom(): int
    {
        return $this->regionCodeFrom;
    }

    /**
     * @param int $regionCodeFrom
     * @return RequestToBuy
     */
    public function setRegionCodeFrom(int $regionCodeFrom): RequestToBuy
    {
        $this->regionCodeFrom = $regionCodeFrom;

        return $this;
    }

    /**
     * @return PubKey
     */
    public function getElGamalPublicKey(): PubKey
    {
        return $this->elGamalPublicKey;
    }

    /**
     * @param PubKey $elGamalPublicKey
     * @return RequestToBuy
     */
    public function setElGamalPublicKey(PubKey $elGamalPublicKey): RequestToBuy
    {
        $this->elGamalPublicKey = $elGamalPublicKey;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'URI' => $this->getUri(),
                'consumer' => $this->getConsumer()->getId(),
                'price' => $this->getPrice()->toArray(),
                'pubKey' => $this->getElGamalPublicKey()->toArray(),
                'region_code_from' => $this->getRegionCodeFrom(),
                'fee' => $this->getFee()->toArray()
            ]
        ];
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
            str_pad(gmp_strval(gmp_init(Math::reverseBytesInt($this->getRegionCodeFrom()), 10), 16), 8, '0', STR_PAD_LEFT),
            implode('', [
                Math::writeUnsignedVarIntHex(strlen($this->getElGamalPublicKey()->getPubKey())),
                Math::byteArrayToHex(Math::stringToByteArray($this->getElGamalPublicKey()->getPubKey()))
            ]),
        ]);

    }

}
