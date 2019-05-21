<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;

class AssetUpdateAdvancedOperation extends BaseOperation
{
    public const OPERATION_TYPE = 40;
    // TODO: Different name ? Is this used ?
    public const OPERATION_NAME = 'asset_update';

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $assetToUpdate;
    /** @var int */
    private $precision;
    /** @var bool */
    private $fixedMaxSupply;

//    /**
//     * AssetUpdateAdvancedOperation constructor.
//     */
//    public function __construct()
//    {
//    }


    /**
     * @param Asset $asset
     * @return AssetUpdateAdvancedOperation
     * @throws ValidationException
     */
    public static function create(Asset $asset): self
    {
        $op = new self();
        $op
            ->setIssuer($asset->getIssuer())
            ->setAssetToUpdate($asset->getId())
            ->setPrecision($asset->getPrecision())
            ->setFixedMaxSupply($asset->getOptions()->isFixedMaxSupply());

        return $op;
    }

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject | string $issuer
     * @return AssetUpdateAdvancedOperation
     * @throws ValidationException
     */
    public function setIssuer($issuer): AssetUpdateAdvancedOperation
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
     * @return AssetUpdateAdvancedOperation
     * @throws ValidationException
     */
    public function setAssetToUpdate($assetToUpdate): AssetUpdateAdvancedOperation
    {
        if (is_string($assetToUpdate)) {
            $assetToUpdate = new ChainObject($assetToUpdate);
        }
        $this->assetToUpdate = $assetToUpdate;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     * @return AssetUpdateAdvancedOperation
     */
    public function setPrecision(int $precision): AssetUpdateAdvancedOperation
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFixedMaxSupply(): bool
    {
        return $this->fixedMaxSupply;
    }

    /**
     * @param bool $fixedMaxSupply
     * @return AssetUpdateAdvancedOperation
     */
    public function setFixedMaxSupply(bool $fixedMaxSupply): AssetUpdateAdvancedOperation
    {
        $this->fixedMaxSupply = $fixedMaxSupply;

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
                'new_precision' => $this->getPrecision(),
                'set_fixed_max_supply' => $this->isFixedMaxSupply()
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
            // TODO: Why is '01' here ? In TS it always shows up, no idea why
            '01',
            str_pad(Math::gmpDecHex($this->getPrecision()), 2, '0', STR_PAD_LEFT),
            $this->isFixedMaxSupply() ? '01' : '00',
            $this->getExtensions() ? '' : '00'
        ]);
    }
}