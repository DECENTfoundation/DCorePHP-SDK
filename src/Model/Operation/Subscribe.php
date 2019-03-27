<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class Subscribe extends BaseOperation
{
    public const OPERATION_TYPE = 26;
    public const OPERATION_NAME = 'subscribe';

    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var AssetAmount */
    private $price;

    public function __construct()
    {
        parent::__construct();
        $this->price = new AssetAmount();
    }

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[from]' => 'from',
                '[to]' => 'to',
                '[price][amount]' => 'price.amount',
                '[price][asset_id]' => 'price.assetId',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getFrom(): ?ChainObject
    {
        return $this->from;
    }

    /**
     * @param ChainObject|string $from
     * @return Subscribe
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setFrom($from): Subscribe
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }

        $this->from = $from;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getTo(): ?ChainObject
    {
        return $this->to;
    }

    /**
     * @param ChainObject|string $to
     * @return Subscribe
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setTo($to): Subscribe
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }

        $this->to = $to;
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
     * @return Subscribe
     */
    public function setPrice(AssetAmount $price): Subscribe
    {
        $this->price = $price;
        return $this;
    }
}
