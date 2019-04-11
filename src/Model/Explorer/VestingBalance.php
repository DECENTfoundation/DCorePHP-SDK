<?php

namespace DCorePHP\Model\Explorer;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class VestingBalance
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $owner;
    /** @var AssetAmount */
    private $balance;
    /** @var int */
    private $policyNumber;
    /** @var VestingPolicy */
    private $policy;

    public function __construct()
    {
        $this->balance = new AssetAmount();
        $this->policy = new VestingPolicy();
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return VestingBalance
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): VestingBalance
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getOwner(): ChainObject
    {
        return $this->owner;
    }

    /**
     * @param ChainObject|string $owner
     * @return VestingBalance
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOwner($owner): VestingBalance
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
    public function getBalance(): AssetAmount
    {
        return $this->balance;
    }

    /**
     * @param AssetAmount $balance
     * @return VestingBalance
     */
    public function setBalance(AssetAmount $balance): VestingBalance
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return int
     */
    public function getPolicyNumber(): int
    {
        return $this->policyNumber;
    }

    /**
     * @param int $policyNumber
     * @return VestingBalance
     */
    public function setPolicyNumber(int $policyNumber): VestingBalance
    {
        $this->policyNumber = $policyNumber;

        return $this;
    }

    /**
     * @return VestingPolicy
     */
    public function getPolicy(): VestingPolicy
    {
        return $this->policy;
    }

    /**
     * @param VestingPolicy $policy
     * @return VestingBalance
     */
    public function setPolicy(VestingPolicy $policy): VestingBalance
    {
        $this->policy = $policy;

        return $this;
    }

}