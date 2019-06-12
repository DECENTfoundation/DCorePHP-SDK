<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class AssetUpdateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 36;
    public const OPERATION_NAME = 'asset_update';

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $assetToUpdate;
    /** @var string */
    private $newDescription;
    /** @var ChainObject */
    private $newIssuer;
    /** @var string */
    private $maxSupply;
    /** @var ExchangeRate */
    private $coreExchangeRate;
    /** @var bool */
    private $exchangeable;

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject | string $issuer
     * @return AssetUpdateOperation
     * @throws ValidationException
     */
    public function setIssuer($issuer): AssetUpdateOperation
    {
        if (is_string($issuer)) {
            $issuer = new ChainObject($issuer);
        }
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAssetToUpdate(): ChainObject
    {
        return $this->assetToUpdate;
    }

    /**
     * @param ChainObject | string $assetToUpdate
     * @return AssetUpdateOperation
     * @throws ValidationException
     */
    public function setAssetToUpdate($assetToUpdate): AssetUpdateOperation
    {
        if (is_string($assetToUpdate)) {
            $assetToUpdate = new ChainObject($assetToUpdate);
        }
        $this->assetToUpdate = $assetToUpdate;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewDescription(): string
    {
        return $this->newDescription;
    }

    /**
     * @param string $newDescription
     * @return AssetUpdateOperation
     */
    public function setNewDescription(string $newDescription): AssetUpdateOperation
    {
        $this->newDescription = $newDescription;

        return $this;
    }

    /**
     * @return ChainObject | null
     */
    public function getNewIssuer(): ?ChainObject
    {
        return $this->newIssuer;
    }

    /**
     * @param ChainObject | string $newIssuer
     * @return AssetUpdateOperation
     * @throws ValidationException
     */
    public function setNewIssuer($newIssuer): AssetUpdateOperation
    {
        if (is_string($newIssuer)) {
            $newIssuer = new ChainObject($newIssuer);
        }
        $this->newIssuer = $newIssuer;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaxSupply(): string
    {
        return $this->maxSupply;
    }

    /**
     * @param string $maxSupply
     * @return AssetUpdateOperation
     */
    public function setMaxSupply(string $maxSupply): AssetUpdateOperation
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    /**
     * @return ExchangeRate
     */
    public function getCoreExchangeRate(): ExchangeRate
    {
        return $this->coreExchangeRate;
    }

    /**
     * @param ExchangeRate $coreExchangeRate
     * @return AssetUpdateOperation
     */
    public function setCoreExchangeRate(ExchangeRate $coreExchangeRate): AssetUpdateOperation
    {
        $this->coreExchangeRate = $coreExchangeRate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExchangeable(): bool
    {
        return $this->exchangeable;
    }

    /**
     * @param bool $exchangeable
     * @return AssetUpdateOperation
     */
    public function setExchangeable(bool $exchangeable): AssetUpdateOperation
    {
        $this->exchangeable = $exchangeable;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'issuer' => $this->getIssuer()->getId(),
                'asset_to_update' => $this->getAssetToUpdate()->getId(),
                'new_description' => $this->getNewDescription(),
                'new_issuer' => $this->getNewIssuer() ? $this->getNewIssuer()->getId() : null,
                'max_supply' => $this->getMaxSupply(),
                'core_exchange_rate' => $this->getCoreExchangeRate()->toArray(),
                'is_exchangeable' => $this->isExchangeable(),
                'extensions' => $this->getExtensions()
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getIssuer()->toBytes(),
            $this->getAssetToUpdate()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getNewDescription()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getNewDescription())),
            $this->getNewIssuer() ? $this->getNewIssuer()->toBytes() : '00',
            Math::getInt64($this->getMaxSupply()),
            $this->getCoreExchangeRate()->toBytes(),
            $this->isExchangeable() ? '01' : '00',
            $this->getExtensions() ?
                VarInt::encodeDecToHex(sizeof($this->getExtensions()))
                . '' // TODO array_map each element toBytes()
                : '00'
        ]);
    }
}
