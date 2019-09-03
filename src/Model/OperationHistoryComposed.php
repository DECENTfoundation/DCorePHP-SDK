<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Operation\TransferOperation;

class OperationHistoryComposed
{

    /** @var TransferOperation */
    private $operation;
    /** @var Account */
    private $from;
    /** @var Account | Content */
    private $to;
    /** @var Asset */
    private $asset;
    /** @var \DateTime */
    private $expiration;

    /**
     * @return TransferOperation
     */
    public function getOperation(): TransferOperation
    {
        return $this->operation;
    }

    /**
     * @param TransferOperation $operation
     *
     * @return OperationHistoryComposed
     */
    public function setOperation(TransferOperation $operation): OperationHistoryComposed
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @return Account
     */
    public function getFrom(): Account
    {
        return $this->from;
    }

    /**
     * @param Account $from
     * @return OperationHistoryComposed
     */
    public function setFrom(Account $from): OperationHistoryComposed
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return Account | Content
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Account | Content $to
     *
     * @return OperationHistoryComposed
     */
    public function setTo($to): OperationHistoryComposed
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * @param Asset $asset
     * @return OperationHistoryComposed
     */
    public function setAsset(Asset $asset): OperationHistoryComposed
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime $expiration
     * @return OperationHistoryComposed
     */
    public function setExpiration(\DateTime $expiration): OperationHistoryComposed
    {
        $this->expiration = $expiration;

        return $this;
    }

}