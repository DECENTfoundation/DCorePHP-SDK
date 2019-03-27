<?php


namespace DCorePHP\Model\Explorer;


class VestingBalance
{
    /** @var string */
    private $id;
    /** @var string */
    private $owner;
    /** @var Asset */
    private $balance;
    /** @var int */
    private $policyNumber;
    /** @var VestingPolicy */
    private $policy;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return VestingBalance
     */
    public function setId(string $id): VestingBalance
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     * @return VestingBalance
     */
    public function setOwner(string $owner): VestingBalance
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Asset
     */
    public function getBalance(): Asset
    {
        return $this->balance;
    }

    /**
     * @param Asset $balance
     * @return VestingBalance
     */
    public function setBalance(Asset $balance): VestingBalance
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