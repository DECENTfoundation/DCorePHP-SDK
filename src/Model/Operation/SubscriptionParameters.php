<?php

namespace DCorePHP\Model\Operation;

class SubscriptionParameters
{
    /** @var bool */
    private $allowSubscription;
    /** @var int */
    private $pricePerSubscribeAmount;
    /** @var int */
    private $subscriptionPeriod;

    /**
     * @return bool
     */
    public function isAllowSubscription(): ?bool
    {
        return $this->allowSubscription;
    }

    /**
     * @param bool $allowSubscription
     * @return SubscriptionParameters
     */
    public function setAllowSubscription(bool $allowSubscription): SubscriptionParameters
    {
        $this->allowSubscription = $allowSubscription;
        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerSubscribeAmount(): ?int
    {
        return $this->pricePerSubscribeAmount;
    }

    /**
     * @param int $pricePerSubscribeAmount
     * @return SubscriptionParameters
     */
    public function setPricePerSubscribeAmount(int $pricePerSubscribeAmount): SubscriptionParameters
    {
        $this->pricePerSubscribeAmount = $pricePerSubscribeAmount;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionPeriod(): ?int
    {
        return $this->subscriptionPeriod;
    }

    /**
     * @param int $subscriptionPeriod
     * @return SubscriptionParameters
     */
    public function setSubscriptionPeriod(int $subscriptionPeriod): SubscriptionParameters
    {
        $this->subscriptionPeriod = $subscriptionPeriod;
        return $this;
    }
}