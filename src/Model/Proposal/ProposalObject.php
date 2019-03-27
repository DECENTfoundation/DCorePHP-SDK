<?php

namespace DCorePHP\Model\Proposal;

use DCorePHP\Model\Transaction;

class ProposalObject
{

    /** @var int */
    private $expirationTime;
    /** @var int */
    private $reviewPeriodTime;
    /** @var Transaction */
    private $proposedTransaction;
    /** @var string[] */
    private $requiredActiveApprovals;
    /** @var string[] */
    private $availableActiveApprovals;
    /** @var string[] */
    private $requiredOwnerApprovals;
    /** @var string[] */
    private $availableOwnerApprovals;
    /** @var string[] */
    private $availableKeyApprovals;

    /**
     * @return int
     */
    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     * @return ProposalObject
     */
    public function setExpirationTime(int $expirationTime): ProposalObject
    {
        $this->expirationTime = $expirationTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getReviewPeriodTime(): int
    {
        return $this->reviewPeriodTime;
    }

    /**
     * @param int $reviewPeriodTime
     * @return ProposalObject
     */
    public function setReviewPeriodTime(int $reviewPeriodTime): ProposalObject
    {
        $this->reviewPeriodTime = $reviewPeriodTime;

        return $this;
    }

    /**
     * @return Transaction
     */
    public function getProposedTransaction(): Transaction
    {
        return $this->proposedTransaction;
    }

    /**
     * @param Transaction $proposedTransaction
     * @return ProposalObject
     */
    public function setProposedTransaction(Transaction $proposedTransaction): ProposalObject
    {
        $this->proposedTransaction = $proposedTransaction;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequiredActiveApprovals(): array
    {
        return $this->requiredActiveApprovals;
    }

    /**
     * @param string[] $requiredActiveApprovals
     * @return ProposalObject
     */
    public function setRequiredActiveApprovals(array $requiredActiveApprovals): ProposalObject
    {
        $this->requiredActiveApprovals = $requiredActiveApprovals;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAvailableActiveApprovals(): array
    {
        return $this->availableActiveApprovals;
    }

    /**
     * @param string[] $availableActiveApprovals
     * @return ProposalObject
     */
    public function setAvailableActiveApprovals(array $availableActiveApprovals): ProposalObject
    {
        $this->availableActiveApprovals = $availableActiveApprovals;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequiredOwnerApprovals(): array
    {
        return $this->requiredOwnerApprovals;
    }

    /**
     * @param string[] $requiredOwnerApprovals
     * @return ProposalObject
     */
    public function setRequiredOwnerApprovals(array $requiredOwnerApprovals): ProposalObject
    {
        $this->requiredOwnerApprovals = $requiredOwnerApprovals;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAvailableOwnerApprovals(): array
    {
        return $this->availableOwnerApprovals;
    }

    /**
     * @param string[] $availableOwnerApprovals
     * @return ProposalObject
     */
    public function setAvailableOwnerApprovals(array $availableOwnerApprovals): ProposalObject
    {
        $this->availableOwnerApprovals = $availableOwnerApprovals;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAvailableKeyApprovals(): array
    {
        return $this->availableKeyApprovals;
    }

    /**
     * @param string[] $availableKeyApprovals
     * @return ProposalObject
     */
    public function setAvailableKeyApprovals(array $availableKeyApprovals): ProposalObject
    {
        $this->availableKeyApprovals = $availableKeyApprovals;

        return $this;
    }

}