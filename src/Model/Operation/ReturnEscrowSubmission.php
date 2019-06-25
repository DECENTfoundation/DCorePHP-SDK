<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class ReturnEscrowSubmission extends BaseOperation
{
    public const OPERATION_TYPE = 42;
    public const OPERATION_NAME = 'return_escrow_submission';

    /** @var AssetAmount */
    private $consumer;
    /** @var AssetAmount */
    private $subscription;

    public function __construct()
    {
        parent::__construct();
        $this->consumer = new AssetAmount();
        $this->subscription = new AssetAmount();
    }

    /**
     * @return AssetAmount
     */
    public function getConsumer(): AssetAmount
    {
        return $this->consumer;
    }

    /**
     * @param AssetAmount|string $consumer
     * @return ReturnEscrowSubmission
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): ReturnEscrowSubmission
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }

        $this->consumer = $consumer;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getSubscription(): AssetAmount
    {
        return $this->subscription;
    }

    /**
     * @param AssetAmount|string $subscription
     * @return ReturnEscrowSubmission
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSubscription($subscription): ReturnEscrowSubmission
    {
        if (is_string($subscription)) {
            $subscription = new ChainObject($subscription);
        }

        $this->subscription = $subscription;
        return $this;
    }
}
