<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\VarInt;

class AssetClaimFeesOperation extends BaseOperation
{
    public const OPERATION_TYPE = 35;
    public const OPERATION_NAME = 'asset_claim_fees_operation';

    /** @var ChainObject */
    private $issuer;
    /** @var AssetAmount*/
    private $uia;
    /** @var AssetAmount */
    private $dct;

    /**
     * AssetClaimFeesOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->uia = new AssetAmount();
        $this->dct = new AssetAmount();
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
     * @return AssetClaimFeesOperation
     * @throws ValidationException
     */
    public function setIssuer($issuer): AssetClaimFeesOperation
    {
        if (is_string($issuer)) {
            $issuer = new ChainObject($issuer);
        }
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getUia(): AssetAmount
    {
        return $this->uia;
    }

    /**
     * @param AssetAmount $uia
     * @return AssetClaimFeesOperation
     */
    public function setUia(AssetAmount $uia): AssetClaimFeesOperation
    {
        $this->uia = $uia;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getDct(): AssetAmount
    {
        return $this->dct;
    }

    /**
     * @param AssetAmount $dct
     * @return AssetClaimFeesOperation
     */
    public function setDct(AssetAmount $dct): AssetClaimFeesOperation
    {
        $this->dct = $dct;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'issuer' => $this->getIssuer()->getId(),
                'uia_asset' => $this->getUia()->toArray(),
                'dct_asset' => $this->getDct()->toArray()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getIssuer()->toBytes(),
            $this->getUia()->toBytes(),
            $this->getDct()->toBytes(),
            // TODO: Extensions Array
            $this->getExtensions() ?
                VarInt::encodeDecToHex(sizeof($this->getExtensions()))
                . '' // TODO array_map for every element in array
                : '00'
        ]);
    }
}
