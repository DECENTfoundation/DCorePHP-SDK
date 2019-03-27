<?php

namespace DCorePHP\Model\General;

class GlobalParameters
{

    /** @var FeeSchedule */
    private $fees;
    /** @var int */
    private $blockInterval;
    /** @var string */
    private $maintenanceInterval;
    /** @var int */
    private $maintenanceSkipSlots;
    /** @var string */
    private $minerProposalReviewPeriod;
    /** @var string */
    private $maximumTransactionSize;
    /** @var string */
    private $maximumBlockSize;
    /** @var string */
    private $maximumTimeUntilExpiration;
    /** @var string */
    private $maximumProposalLifetime;
    /** @var int */
    private $maximumAssetFeedPublishers;
    /** @var int */
    private $maximumMinerCount;
    /** @var int */
    private $maximumAuthorityMembership;
    /** @var string */
    private $cashBackVestingPeriodSeconds;
    /** @var string */
    private $cashBackVestingThreshold;
    /** @var int */
    private $maxPredicateOpCode;
    /** @var int */
    private $maxAuthorityDepth;
    /** @var array */
    private $extensions;

    public function __construct()
    {
        $this->fees = new FeeSchedule();
    }

    /**
     * @return FeeSchedule
     */
    public function getFees(): FeeSchedule
    {
        return $this->fees;
    }

    /**
     * @param FeeSchedule $fees
     * @return GlobalParameters
     */
    public function setFees(FeeSchedule $fees): GlobalParameters
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockInterval(): int
    {
        return $this->blockInterval;
    }

    /**
     * @param int $blockInterval
     * @return GlobalParameters
     */
    public function setBlockInterval(int $blockInterval): GlobalParameters
    {
        $this->blockInterval = $blockInterval;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaintenanceInterval(): string
    {
        return $this->maintenanceInterval;
    }

    /**
     * @param string $maintenanceInterval
     * @return GlobalParameters
     */
    public function setMaintenanceInterval(string $maintenanceInterval): GlobalParameters
    {
        $this->maintenanceInterval = $maintenanceInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaintenanceSkipSlots(): int
    {
        return $this->maintenanceSkipSlots;
    }

    /**
     * @param int $maintenanceSkipSlots
     * @return GlobalParameters
     */
    public function setMaintenanceSkipSlots(int $maintenanceSkipSlots): GlobalParameters
    {
        $this->maintenanceSkipSlots = $maintenanceSkipSlots;

        return $this;
    }

    /**
     * @return string
     */
    public function getMinerProposalReviewPeriod(): string
    {
        return $this->minerProposalReviewPeriod;
    }

    /**
     * @param string $minerProposalReviewPeriod
     * @return GlobalParameters
     */
    public function setMinerProposalReviewPeriod(string $minerProposalReviewPeriod): GlobalParameters
    {
        $this->minerProposalReviewPeriod = $minerProposalReviewPeriod;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaximumTransactionSize(): string
    {
        return $this->maximumTransactionSize;
    }

    /**
     * @param string $maximumTransactionSize
     * @return GlobalParameters
     */
    public function setMaximumTransactionSize(string $maximumTransactionSize): GlobalParameters
    {
        $this->maximumTransactionSize = $maximumTransactionSize;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaximumBlockSize(): string
    {
        return $this->maximumBlockSize;
    }

    /**
     * @param string $maximumBlockSize
     * @return GlobalParameters
     */
    public function setMaximumBlockSize(string $maximumBlockSize): GlobalParameters
    {
        $this->maximumBlockSize = $maximumBlockSize;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaximumTimeUntilExpiration(): string
    {
        return $this->maximumTimeUntilExpiration;
    }

    /**
     * @param string $maximumTimeUntilExpiration
     * @return GlobalParameters
     */
    public function setMaximumTimeUntilExpiration(string $maximumTimeUntilExpiration): GlobalParameters
    {
        $this->maximumTimeUntilExpiration = $maximumTimeUntilExpiration;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaximumProposalLifetime(): string
    {
        return $this->maximumProposalLifetime;
    }

    /**
     * @param string $maximumProposalLifetime
     * @return GlobalParameters
     */
    public function setMaximumProposalLifetime(string $maximumProposalLifetime): GlobalParameters
    {
        $this->maximumProposalLifetime = $maximumProposalLifetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumAssetFeedPublishers(): int
    {
        return $this->maximumAssetFeedPublishers;
    }

    /**
     * @param int $maximumAssetFeedPublishers
     * @return GlobalParameters
     */
    public function setMaximumAssetFeedPublishers(int $maximumAssetFeedPublishers): GlobalParameters
    {
        $this->maximumAssetFeedPublishers = $maximumAssetFeedPublishers;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumMinerCount(): int
    {
        return $this->maximumMinerCount;
    }

    /**
     * @param int $maximumMinerCount
     * @return GlobalParameters
     */
    public function setMaximumMinerCount(int $maximumMinerCount): GlobalParameters
    {
        $this->maximumMinerCount = $maximumMinerCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumAuthorityMembership(): int
    {
        return $this->maximumAuthorityMembership;
    }

    /**
     * @param int $maximumAuthorityMembership
     * @return GlobalParameters
     */
    public function setMaximumAuthorityMembership(int $maximumAuthorityMembership): GlobalParameters
    {
        $this->maximumAuthorityMembership = $maximumAuthorityMembership;

        return $this;
    }

    /**
     * @return string
     */
    public function getCashBackVestingPeriodSeconds(): string
    {
        return $this->cashBackVestingPeriodSeconds;
    }

    /**
     * @param string $cashBackVestingPeriodSeconds
     * @return GlobalParameters
     */
    public function setCashBackVestingPeriodSeconds(string $cashBackVestingPeriodSeconds): GlobalParameters
    {
        $this->cashBackVestingPeriodSeconds = $cashBackVestingPeriodSeconds;

        return $this;
    }

    /**
     * @return string
     */
    public function getCashBackVestingThreshold(): string
    {
        return $this->cashBackVestingThreshold;
    }

    /**
     * @param string $cashBackVestingThreshold
     * @return GlobalParameters
     */
    public function setCashBackVestingThreshold(string $cashBackVestingThreshold): GlobalParameters
    {
        $this->cashBackVestingThreshold = $cashBackVestingThreshold;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPredicateOpcode(): int
    {
        return $this->maxPredicateOpCode;
    }

    /**
     * @param int $maxPredicateOpCode
     * @return GlobalParameters
     */
    public function setMaxPredicateOpcode(int $maxPredicateOpCode): GlobalParameters
    {
        $this->maxPredicateOpCode = $maxPredicateOpCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAuthorityDepth(): int
    {
        return $this->maxAuthorityDepth;
    }

    /**
     * @param int $maxAuthorityDepth
     * @return GlobalParameters
     */
    public function setMaxAuthorityDepth(int $maxAuthorityDepth): GlobalParameters
    {
        $this->maxAuthorityDepth = $maxAuthorityDepth;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return GlobalParameters
     */
    public function setExtensions(array $extensions): GlobalParameters
    {
        $this->extensions = $extensions;

        return $this;
    }

}