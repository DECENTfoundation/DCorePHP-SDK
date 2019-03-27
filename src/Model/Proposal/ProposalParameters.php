<?php


namespace DCorePHP\Model\Proposal;


class ProposalParameters
{

    /** @var array */
    private $parameters;
    /** @var int */
    private $scale;
    /** @var int */
    private $blockInterval;
    /** @var int */
    private $maintenanceInterval;
    /** @var int */
    private $maintenanceSkipSlots;
    /** @var int */
    private $minerProposalReviewPeriod;
    /** @var int */
    private $maximumTransactionSize;
    /** @var int */
    private $maximumBlockSize;
    /** @var int */
    private $maximumTimeUntilExpiration;
    /** @var int */
    private $maximumProposalLifetime;
    /** @var int */
    private $maximumAssetFeedPublishers;
    /** @var int */
    private $maximumMinerCount;
    /** @var int */
    private $maximumAuthorityMembership;
    /** @var int */
    private $cashbackVestingPeriodSeconds;
    /** @var int */
    private $cashbackVestingThreshold;
    /** @var int */
    private $maxPredicateOpcode;
    /** @var int */
    private $maxAuthorityDepth;
    /** @var array */
    private $extensions;

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return ProposalParameters
     */
    public function setParameters(array $parameters): ProposalParameters
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * @param int $scale
     * @return ProposalParameters
     */
    public function setScale(int $scale): ProposalParameters
    {
        $this->scale = $scale;

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
     * @return ProposalParameters
     */
    public function setBlockInterval(int $blockInterval): ProposalParameters
    {
        $this->blockInterval = $blockInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaintenanceInterval(): int
    {
        return $this->maintenanceInterval;
    }

    /**
     * @param int $maintenanceInterval
     * @return ProposalParameters
     */
    public function setMaintenanceInterval(int $maintenanceInterval): ProposalParameters
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
     * @return ProposalParameters
     */
    public function setMaintenanceSkipSlots(int $maintenanceSkipSlots): ProposalParameters
    {
        $this->maintenanceSkipSlots = $maintenanceSkipSlots;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinerProposalReviewPeriod(): int
    {
        return $this->minerProposalReviewPeriod;
    }

    /**
     * @param int $minerProposalReviewPeriod
     * @return ProposalParameters
     */
    public function setMinerProposalReviewPeriod(int $minerProposalReviewPeriod): ProposalParameters
    {
        $this->minerProposalReviewPeriod = $minerProposalReviewPeriod;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumTransactionSize(): int
    {
        return $this->maximumTransactionSize;
    }

    /**
     * @param int $maximumTransactionSize
     * @return ProposalParameters
     */
    public function setMaximumTransactionSize(int $maximumTransactionSize): ProposalParameters
    {
        $this->maximumTransactionSize = $maximumTransactionSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumBlockSize(): int
    {
        return $this->maximumBlockSize;
    }

    /**
     * @param int $maximumBlockSize
     * @return ProposalParameters
     */
    public function setMaximumBlockSize(int $maximumBlockSize): ProposalParameters
    {
        $this->maximumBlockSize = $maximumBlockSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumTimeUntilExpiration(): int
    {
        return $this->maximumTimeUntilExpiration;
    }

    /**
     * @param int $maximumTimeUntilExpiration
     * @return ProposalParameters
     */
    public function setMaximumTimeUntilExpiration(int $maximumTimeUntilExpiration): ProposalParameters
    {
        $this->maximumTimeUntilExpiration = $maximumTimeUntilExpiration;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumProposalLifetime(): int
    {
        return $this->maximumProposalLifetime;
    }

    /**
     * @param int $maximumProposalLifetime
     * @return ProposalParameters
     */
    public function setMaximumProposalLifetime(int $maximumProposalLifetime): ProposalParameters
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
     * @return ProposalParameters
     */
    public function setMaximumAssetFeedPublishers(int $maximumAssetFeedPublishers): ProposalParameters
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
     * @return ProposalParameters
     */
    public function setMaximumMinerCount(int $maximumMinerCount): ProposalParameters
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
     * @return ProposalParameters
     */
    public function setMaximumAuthorityMembership(int $maximumAuthorityMembership): ProposalParameters
    {
        $this->maximumAuthorityMembership = $maximumAuthorityMembership;

        return $this;
    }

    /**
     * @return int
     */
    public function getCashbackVestingPeriodSeconds(): int
    {
        return $this->cashbackVestingPeriodSeconds;
    }

    /**
     * @param int $cashbackVestingPeriodSeconds
     * @return ProposalParameters
     */
    public function setCashbackVestingPeriodSeconds(int $cashbackVestingPeriodSeconds): ProposalParameters
    {
        $this->cashbackVestingPeriodSeconds = $cashbackVestingPeriodSeconds;

        return $this;
    }

    /**
     * @return int
     */
    public function getCashbackVestingThreshold(): int
    {
        return $this->cashbackVestingThreshold;
    }

    /**
     * @param int $cashbackVestingThreshold
     * @return ProposalParameters
     */
    public function setCashbackVestingThreshold(int $cashbackVestingThreshold): ProposalParameters
    {
        $this->cashbackVestingThreshold = $cashbackVestingThreshold;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPredicateOpcode(): int
    {
        return $this->maxPredicateOpcode;
    }

    /**
     * @param int $maxPredicateOpcode
     * @return ProposalParameters
     */
    public function setMaxPredicateOpcode(int $maxPredicateOpcode): ProposalParameters
    {
        $this->maxPredicateOpcode = $maxPredicateOpcode;

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
     * @return ProposalParameters
     */
    public function setMaxAuthorityDepth(int $maxAuthorityDepth): ProposalParameters
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
     * @return ProposalParameters
     */
    public function setExtensions(array $extensions): ProposalParameters
    {
        $this->extensions = $extensions;

        return $this;
    }

}