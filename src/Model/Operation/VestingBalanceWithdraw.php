<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class VestingBalanceWithdraw extends BaseOperation
{
    public const OPERATION_TYPE = 17;
    public const OPERATION_NAME = 'vesting_balance_withdraw';

    /** @var ChainObject */
    private $vestingBalance;
    /** @var ChainObject */
    private $owner;
    /** @var AssetAmount */
    private $amount;

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
                '[vesting_balance]' => 'vestingBalance',
                '[owner]' => 'owner',
                '[amount][amount]' => 'amount.amount',
                '[amount][asset_id]' => 'amount.assetId',
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
    public function getVestingBalance(): ?ChainObject
    {
        return $this->vestingBalance;
    }

    /**
     * @param ChainObject|string $vestingBalance
     * @return VestingBalanceWithdraw
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setVestingBalance($vestingBalance): VestingBalanceWithdraw
    {
        if (is_string($vestingBalance)) {
            $vestingBalance = new ChainObject($vestingBalance);
        }

        $this->vestingBalance = $vestingBalance;
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
     * @return VestingBalanceWithdraw
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOwner($owner): VestingBalanceWithdraw
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
     * @return VestingBalanceWithdraw
     */
    public function setAmount(AssetAmount $amount): VestingBalanceWithdraw
    {
        $this->amount = $amount;
        return $this;
    }
}
