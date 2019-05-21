<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Operation\Transfer2;

class OperationHistoryComposed
{

    /** @var Transfer2 */
    private $operation;
    /** @var Account */
    private $from;
    /** @var Account | ContentObject */
    private $to;
    /** @var Asset */
    private $asset;
    /** @var \DateTime */
    private $expiration;

    /**
     * @return Transfer2
     */
    public function getOperation(): Transfer2
    {
        return $this->operation;
    }

    /**
     * @param Transfer2 $operation
     * @return OperationHistoryComposed
     */
    public function setOperation(Transfer2 $operation): OperationHistoryComposed
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
     * @return Account | ContentObject
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Account | ContentObject $to
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