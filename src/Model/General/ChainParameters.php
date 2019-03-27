<?php

namespace DCorePHP\Model\General;

class ChainParameters
{

    /** @var int */
    private $minMinerCount;

    /** @var int */
    private $specialAccounts;

    /** @var int */
    private $specialAssets;

    /**
     * @return int
     */
    public function getMinMinerCount(): int
    {
        return $this->minMinerCount;
    }

    /**
     * @param int $minMinerCount
     * @return ChainParameters
     */
    public function setMinMinerCount(int $minMinerCount): ChainParameters
    {
        $this->minMinerCount = $minMinerCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpecialAccounts(): int
    {
        return $this->specialAccounts;
    }

    /**
     * @param int $specialAccounts
     * @return ChainParameters
     */
    public function setSpecialAccounts(int $specialAccounts): ChainParameters
    {
        $this->specialAccounts = $specialAccounts;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpecialAssets(): int
    {
        return $this->specialAssets;
    }

    /**
     * @param int $specialAssets
     * @return ChainParameters
     */
    public function setSpecialAssets(int $specialAssets): ChainParameters
    {
        $this->specialAssets = $specialAssets;

        return $this;
    }

}