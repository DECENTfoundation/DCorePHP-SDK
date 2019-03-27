<?php

namespace DCorePHP\Model\Asset;

class RealSupply
{
    /** @var string */
    private $accountBalances;
    /** @var string */
    private $vestingBalances;
    /** @var int */
    private $escrows;
    /** @var string */
    private $pools;

    /**
     * @return string
     */
    public function getAccountBalances(): string
    {
        return $this->accountBalances;
    }

    /**
     * @param string $accountBalances
     * @return RealSupply
     */
    public function setAccountBalances(string $accountBalances): RealSupply
    {
        $this->accountBalances = $accountBalances;

        return $this;
    }

    /**
     * @return string
     */
    public function getVestingBalances(): string
    {
        return $this->vestingBalances;
    }

    /**
     * @param string $vestingBalances
     * @return RealSupply
     */
    public function setVestingBalances(string $vestingBalances): RealSupply
    {
        $this->vestingBalances = $vestingBalances;

        return $this;
    }

    /**
     * @return int
     */
    public function getEscrows(): int
    {
        return $this->escrows;
    }

    /**
     * @param int $escrows
     * @return RealSupply
     */
    public function setEscrows(int $escrows): RealSupply
    {
        $this->escrows = $escrows;

        return $this;
    }

    /**
     * @return string
     */
    public function getPools(): string
    {
        return $this->pools;
    }

    /**
     * @param string $pools
     * @return RealSupply
     */
    public function setPools(string $pools): RealSupply
    {
        $this->pools = $pools;

        return $this;
    }

}