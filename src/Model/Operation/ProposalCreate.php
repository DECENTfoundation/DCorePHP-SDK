<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class ProposalCreate extends BaseOperation
{
    public const OPERATION_TYPE = 9;
    public const OPERATION_NAME = 'proposal_create';

    /** @var ChainObject */
    private $feePayingAccount;
    /** @var \DateTime */
    private $expirationTime;
    /** @var array */
    private $proposedOps; // @todo split into separate model
    /** @var int */
    private $reviewPeriodSeconds;
    /** @var array */
    private $extensions = [];

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[fee_paying_account]' => 'feePayingAccount',
                '[expiration_time]' => 'expirationTime',
                '[proposed_ops]' => 'proposedOps',
                '[review_period_seconds]' => 'reviewPeriodSeconds',
                '[extensions]' => 'extensions',
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
    public function getFeePayingAccount(): ?ChainObject
    {
        return $this->feePayingAccount;
    }

    /**
     * @param ChainObject|string $feePayingAccount
     * @return ProposalCreate
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setFeePayingAccount($feePayingAccount): ProposalCreate
    {
        if (is_string($feePayingAccount)) {
            $feePayingAccount = new ChainObject($feePayingAccount);
        }

        $this->feePayingAccount = $feePayingAccount;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationTime(): ?\DateTime
    {
        return $this->expirationTime;
    }

    /**
     * @param \DateTime|string $expirationTime
     * @return ProposalCreate
     * @throws \Exception
     */
    public function setExpirationTime($expirationTime): ProposalCreate
    {
        $this->expirationTime = $expirationTime instanceof \DateTime ? $expirationTime : new \DateTime($expirationTime);

        return $this;
    }

    /**
     * @return array
     */
    public function getProposedOps(): ?array
    {
        return $this->proposedOps;
    }

    /**
     * @param array $proposedOps
     * @return ProposalCreate
     */
    public function setProposedOps(array $proposedOps): ProposalCreate
    {
        $this->proposedOps = $proposedOps;
        return $this;
    }

    /**
     * @return int
     */
    public function getReviewPeriodSeconds(): ?int
    {
        return $this->reviewPeriodSeconds;
    }

    /**
     * @param int $reviewPeriodSeconds
     * @return ProposalCreate
     */
    public function setReviewPeriodSeconds(?int $reviewPeriodSeconds): ProposalCreate
    {
        $this->reviewPeriodSeconds = $reviewPeriodSeconds;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return ProposalCreate
     */
    public function setExtensions(array $extensions): ProposalCreate
    {
        $this->extensions = $extensions;
        return $this;
    }
}
