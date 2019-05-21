<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class AssetReserveOperation extends BaseOperation
{
    public const OPERATION_TYPE = 34;
    public const OPERATION_NAME = 'asset_reserve_operation';

    /** @var ChainObject */
    private $payer;
    /** @var AssetAmount */
    private $amount;

    /**
     * AssetReserveOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->amount = new AssetAmount();
    }

    /**
     * @return ChainObject
     */
    public function getPayer(): ChainObject
    {
        return $this->payer;
    }

    /**
     * @param ChainObject | string $payer
     * @return AssetReserveOperation
     * @throws ValidationException
     */
    public function setPayer($payer): AssetReserveOperation
    {
        if (is_string($payer)) {
            $payer = new ChainObject($payer);
        }
        $this->payer = $payer;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getAmount(): AssetAmount
    {
        return $this->amount;
    }

    /**
     * @param AssetAmount $amount
     * @return AssetReserveOperation
     */
    public function setAmount(AssetAmount $amount): AssetReserveOperation
    {
        $this->amount = $amount;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'payer' => $this->getPayer()->getId(),
                'amount_to_reserve' => $this->getAmount()->toArray()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getPayer()->toBytes(),
            $this->getAmount()->toBytes(),
            // TODO: Extensions Array
            $this->getExtensions() ? '01' : '00'
        ]);
    }
}
