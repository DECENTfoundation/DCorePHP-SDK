<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class PaySeederOperation extends BaseOperation
{
    public const OPERATION_TYPE = 44;
    public const OPERATION_NAME = 'pay_seeder';

    /** @var AssetAmount */
    private $escrow;
    /** @var ChainObject */
    private $consumer;
    /** @var ChainObject */
    private $buying;

    public function __construct()
    {
        parent::__construct();
        $this->escrow = new AssetAmount();
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
                '[escrow][amount]' => 'escrow.amount',
                '[escrow][asset_id]' => 'escrow.assetId',
                '[consumer]' => 'consumer',
                '[buying]' => 'buying',
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
     * @return AssetAmount
     */
    public function getEscrow(): ?AssetAmount
    {
        return $this->escrow;
    }

    /**
     * @param AssetAmount $escrow
     * @return self
     */
    public function setEscrow(AssetAmount $escrow): self
    {
        $this->escrow = $escrow;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ?ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject|string $consumer
     * @return self
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): self
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }

        $this->consumer = $consumer;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getBuying(): ?ChainObject
    {
        return $this->buying;
    }

    /**
     * @param ChainObject|string $buying
     * @return self
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setBuying($buying): self
    {
        if (is_string($buying)) {
            $buying = new ChainObject($buying);
        }

        $this->buying = $buying;
        return $this;
    }
}