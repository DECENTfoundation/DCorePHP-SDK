<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;

class BalanceChange
{
    /** @var OperationHistory */
    private $operation;
    /** @var Balance */
    private $balance;
    /** @var AssetAmount */
    private $fee;

    public function __construct()
    {
        $this->operation = new OperationHistory();
        $this->balance = new Balance();
        $this->fee = new AssetAmount();
    }

    /**
     * @return OperationHistory
     */
    public function getOperation(): OperationHistory
    {
        return $this->operation;
    }

    /**
     * @param OperationHistory $operation
     * @return BalanceChange
     */
    public function setOperation(OperationHistory $operation): BalanceChange
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @return Balance
     */
    public function getBalance(): Balance
    {
        return $this->balance;
    }

    /**
     * @param Balance $balance
     * @return BalanceChange
     */
    public function setBalance(Balance $balance): BalanceChange
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getFee(): AssetAmount
    {
        return $this->fee;
    }

    /**
     * @param AssetAmount $fee
     * @return BalanceChange
     */
    public function setFee(AssetAmount $fee): BalanceChange
    {
        $this->fee = $fee;

        return $this;
    }
}