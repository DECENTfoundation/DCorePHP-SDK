<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class VestingBalanceCreate extends BaseOperation
{
    public const OPERATION_TYPE = 16;
    public const OPERATION_NAME = 'vesting_balance_create';

    /** @var ChainObject */
    private $creator;
    /** @var ChainObject */
    private $owner;
    /** @var AssetAmount */
    private $amount;
    /** @var array */
    private $policy;

    public function __construct()
    {
        parent::__construct();
        $this->amount = new AssetAmount();
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
                '[creator]' => 'creator',
                '[owner]' => 'owner',
                '[amount][amount]' => 'amount.amount',
                '[amount][asset_id]' => 'amount.assetId',
                '[policy]' => 'policy',
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
    public function getCreator(): ?ChainObject
    {
        return $this->creator;
    }

    /**
     * @param ChainObject|string $creator
     * @return VestingBalanceCreate
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setCreator($creator): VestingBalanceCreate
    {
        if (is_string($creator)) {
            $creator = new ChainObject($creator);
        }

        $this->creator = $creator;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getOwner(): ?ChainObject
    {
        return $this->owner;
    }

    /**
     * @param ChainObject|string $owner
     * @return VestingBalanceCreate
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOwner($owner): VestingBalanceCreate
    {
        if (is_string($owner)) {
            $owner = new ChainObject($owner);
        }

        $this->owner = $owner;
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
     * @return VestingBalanceCreate
     */
    public function setAmount(AssetAmount $amount): VestingBalanceCreate
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return array
     */
    public function getPolicy(): ?array
    {
        return $this->policy;
    }

    /**
     * @param array $policy
     * @return VestingBalanceCreate
     */
    public function setPolicy(array $policy): VestingBalanceCreate
    {
        $this->policy = $policy;
        return $this;
    }
}
