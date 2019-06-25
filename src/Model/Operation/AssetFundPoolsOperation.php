<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\VarInt;

class AssetFundPoolsOperation extends BaseOperation
{
    public const OPERATION_TYPE = 33;
    public const OPERATION_NAME = 'asset_fund_pools_operation';

    /** @var ChainObject */
    private $from;
    /** @var AssetAmount */
    private $uia;
    /** @var AssetAmount */
    private $dct;

    /**
     * AssetFundPoolsOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->uia = new AssetAmount();
        $this->dct= new AssetAmount();
    }


    /**
     * @return ChainObject
     */
    public function getFrom(): ChainObject
    {
        return $this->from;
    }

    /**
     * @param ChainObject | string $from
     * @return AssetFundPoolsOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setFrom($from): AssetFundPoolsOperation
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }
        $this->from = $from;

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
     * @return AssetFundPoolsOperation
     */
    public function setUia(AssetAmount $uia): AssetFundPoolsOperation
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
     * @return AssetFundPoolsOperation
     */
    public function setDct(AssetAmount $dct): AssetFundPoolsOperation
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
                'from_account' => $this->getFrom()->getId(),
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
            $this->getFrom()->toBytes(),
            $this->getUia()->toBytes(),
            $this->getDct()->toBytes(),
            $this->getExtensions() ?
                VarInt::encodeDecToHex(sizeof($this->getExtensions()))
                . '' // TODO array_map each element toBytes()
                : '00'
        ]);
    }
}
