<?php

namespace DCorePHP\Model;

class DynamicGlobalProps
{
    /** @var string */
    private $id;
    /** @var int */
    private $headBlockNumber;
    /** @var string */
    private $headBlockId;
    /** @var \DateTime */
    private $time;
    /** @var \DateTime */
    private $expiration;
    /** @var int */
    private $expirationInterval = 30; // seconds
    /** @var string */
    private $currentMiner;
    /** @var \DateTime */
    private $nextMaintenanceTime;
    /** @var \DateTime */
    private $lastBudgetTime;
    /** @var int */
    private $unspentFeeBudget;
    /** @var string */
    private $minedRewards;
    /** @var int */
    private $minerBudgetFromFees;
    /** @var string */
    private $minerBudgetFromRewards;
    /** @var int */
    private $accountsRegisteredThisInterval;
    /** @var int */
    private $recentlyMissedCount;
    /** @var int */
    private $currentAslot;
    /** @var string */
    private $recentSlotsFilled;
    /** @var int */
    private $dynamicFlags;
    /** @var int */
    private $lastIrreversibleBlockNum;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return DynamicGlobalProps
     */
    public function setId(string $id): DynamicGlobalProps
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeadBlockNumber(): int
    {
        return $this->headBlockNumber;
    }

    /**
     * @param int $headBlockNumber
     * @return DynamicGlobalProps
     */
    public function setHeadBlockNumber(int $headBlockNumber): DynamicGlobalProps
    {
        $this->headBlockNumber = $headBlockNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadBlockId(): string
    {
        return $this->headBlockId;
    }

    /**
     * @param string $headBlockId
     * @return DynamicGlobalProps
     */
    public function setHeadBlockId(string $headBlockId): DynamicGlobalProps
    {
        $this->headBlockId = $headBlockId;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime|string $time
     * @return DynamicGlobalProps
     */
    public function setTime($time): DynamicGlobalProps
    {
        $this->time = $time instanceof \DateTime ? $time : new \DateTime($time, new \DateTimeZone('UTC'));
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentMiner(): string
    {
        return $this->currentMiner;
    }

    /**
     * @param string $currentMiner
     * @return DynamicGlobalProps
     */
    public function setCurrentMiner(string $currentMiner): DynamicGlobalProps
    {
        $this->currentMiner = $currentMiner;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNextMaintenanceTime(): \DateTime
    {
        return $this->nextMaintenanceTime;
    }

    /**
     * @param \DateTime|string $nextMaintenanceTime
     * @return DynamicGlobalProps
     */
    public function setNextMaintenanceTime($nextMaintenanceTime): DynamicGlobalProps
    {
        $this->nextMaintenanceTime = $nextMaintenanceTime instanceof \DateTime ? $nextMaintenanceTime : new \DateTime($nextMaintenanceTime, new \DateTimeZone('UTC'));
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastBudgetTime(): \DateTime
    {
        return $this->lastBudgetTime;
    }

    /**
     * @param \DateTime|string $lastBudgetTime
     * @return DynamicGlobalProps
     */
    public function setLastBudgetTime($lastBudgetTime): DynamicGlobalProps
    {
        $this->lastBudgetTime = $lastBudgetTime instanceof \DateTime ? $lastBudgetTime : new \DateTime($lastBudgetTime, new \DateTimeZone('UTC'));
        return $this;
    }

    /**
     * @return int
     */
    public function getUnspentFeeBudget(): int
    {
        return $this->unspentFeeBudget;
    }

    /**
     * @param int $unspentFeeBudget
     * @return DynamicGlobalProps
     */
    public function setUnspentFeeBudget(int $unspentFeeBudget): DynamicGlobalProps
    {
        $this->unspentFeeBudget = $unspentFeeBudget;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinedRewards(): string
    {
        return $this->minedRewards;
    }

    /**
     * @param string $minedRewards
     * @return DynamicGlobalProps
     */
    public function setMinedRewards(string $minedRewards): DynamicGlobalProps
    {
        $this->minedRewards = $minedRewards;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinerBudgetFromFees(): int
    {
        return $this->minerBudgetFromFees;
    }

    /**
     * @param int $minerBudgetFromFees
     * @return DynamicGlobalProps
     */
    public function setMinerBudgetFromFees(int $minerBudgetFromFees): DynamicGlobalProps
    {
        $this->minerBudgetFromFees = $minerBudgetFromFees;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinerBudgetFromRewards(): string
    {
        return $this->minerBudgetFromRewards;
    }

    /**
     * @param string $minerBudgetFromRewards
     * @return DynamicGlobalProps
     */
    public function setMinerBudgetFromRewards(string $minerBudgetFromRewards): DynamicGlobalProps
    {
        $this->minerBudgetFromRewards = $minerBudgetFromRewards;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccountsRegisteredThisInterval(): int
    {
        return $this->accountsRegisteredThisInterval;
    }

    /**
     * @param int $accountsRegisteredThisInterval
     * @return DynamicGlobalProps
     */
    public function setAccountsRegisteredThisInterval(int $accountsRegisteredThisInterval): DynamicGlobalProps
    {
        $this->accountsRegisteredThisInterval = $accountsRegisteredThisInterval;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecentlyMissedCount(): int
    {
        return $this->recentlyMissedCount;
    }

    /**
     * @param int $recentlyMissedCount
     * @return DynamicGlobalProps
     */
    public function setRecentlyMissedCount(int $recentlyMissedCount): DynamicGlobalProps
    {
        $this->recentlyMissedCount = $recentlyMissedCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentAslot(): int
    {
        return $this->currentAslot;
    }

    /**
     * @param int $currentAslot
     * @return DynamicGlobalProps
     */
    public function setCurrentAslot(int $currentAslot): DynamicGlobalProps
    {
        $this->currentAslot = $currentAslot;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecentSlotsFilled(): string
    {
        return $this->recentSlotsFilled;
    }

    /**
     * @param string $recentSlotsFilled
     * @return DynamicGlobalProps
     */
    public function setRecentSlotsFilled(string $recentSlotsFilled): DynamicGlobalProps
    {
        $this->recentSlotsFilled = $recentSlotsFilled;
        return $this;
    }

    /**
     * @return int
     */
    public function getDynamicFlags(): int
    {
        return $this->dynamicFlags;
    }

    /**
     * @param int $dynamicFlags
     * @return DynamicGlobalProps
     */
    public function setDynamicFlags(int $dynamicFlags): DynamicGlobalProps
    {
        $this->dynamicFlags = $dynamicFlags;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastIrreversibleBlockNum(): int
    {
        return $this->lastIrreversibleBlockNum;
    }

    /**
     * @param int $lastIrreversibleBlockNum
     * @return DynamicGlobalProps
     */
    public function setLastIrreversibleBlockNum(int $lastIrreversibleBlockNum): DynamicGlobalProps
    {
        $this->lastIrreversibleBlockNum = $lastIrreversibleBlockNum;
        return $this;
    }

    /**
     * @return int
     */
    public function getRefBlockNum(): int
    {
        return ($this->getHeadBlockNumber() & 0xFFFF);
    }

    /**
     * @return int
     */
    public function getRefBlockPrefix(): int
    {
        $headBlockId = substr($this->getHeadBlockId(), 8, 8);
        $headBlockId = str_split($headBlockId, 2);
        $headBlockId = array_reverse($headBlockId);
        $headBlockId = implode('', $headBlockId);
        $headBlockId = hexdec($headBlockId);

        return $headBlockId;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        if (!$this->expiration) {
            $this->expiration = (clone $this->getTime())->modify("+{$this->getExpirationInterval()} seconds");
        }

        return $this->expiration;
    }

    /**
     * @return int
     */
    public function getExpirationInterval(): int
    {
        return $this->expirationInterval;
    }

    /**
     * @param int $expirationInterval
     * @return DynamicGlobalProps
     */
    public function setExpirationInterval(int $expirationInterval): DynamicGlobalProps
    {
        $this->expirationInterval = $expirationInterval;
        return $this;
    }
}