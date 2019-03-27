<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class AutomaticRenewalOfSubscription extends BaseOperation
{
    public const OPERATION_TYPE = 28;
    public const OPERATION_NAME = 'automatic_renewal_of_subscription';

    /** @var ChainObject */
    private $consumer;
    /** @var ChainObject */
    private $subscription;
    /** @var bool */
    private $automaticRenewal;

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[consumer]' => 'consumer',
                '[subscription]' => 'subscription',
                '[automatic_renewal]' => 'automaticRenewal',
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
    public function getConsumer(): ?ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject|string $consumer
     * @return AutomaticRenewalOfSubscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): AutomaticRenewalOfSubscription
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }

        $this->consumer = $consumer;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getSubscription(): ?ChainObject
    {
        return $this->subscription;
    }

    /**
     * @param ChainObject|string $subscription
     * @return AutomaticRenewalOfSubscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSubscription($subscription): AutomaticRenewalOfSubscription
    {
        if (is_string($subscription)) {
            $subscription = new ChainObject($subscription);
        }

        $this->subscription = $subscription;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutomaticRenewal(): ?bool
    {
        return $this->automaticRenewal;
    }

    /**
     * @param bool $automaticRenewal
     * @return AutomaticRenewalOfSubscription
     */
    public function setAutomaticRenewal(bool $automaticRenewal): AutomaticRenewalOfSubscription
    {
        $this->automaticRenewal = $automaticRenewal;
        return $this;
    }
}
