<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\PubKey;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

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
     * @throws ValidationException
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
        return [
            self::OPERATION_TYPE,
            [
                'URI' => $this->getUri(),
                'consumer' => $this->getConsumer()->getId(),
                'price' => $this->getPrice()->toArray(),
                'pubKey' => $this->getPublicElGamal()->toArray(),
                'region_code_from' => $this->getRegionCode(),
                'fee' => $this->getFee()->toArray()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getUri()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getUri())),
            $this->getConsumer()->toBytes(),
            $this->getPrice()->toBytes(),
            Math::getInt32($this->getRegionCode()),
            $this->getPublicElGamal()->toBytes()
        ]);
    }
}
