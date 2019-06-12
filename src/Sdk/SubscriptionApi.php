<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Subscription\Subscription;
use DCorePHP\Net\Model\Request\GetSubscription;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByConsumer;
use DCorePHP\Net\Model\Request\ListSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListSubscriptionsByConsumer;

class SubscriptionApi extends BaseApi implements SubscriptionApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(ChainObject $id): Subscription
    {
        return $this->dcoreApi->requestWebsocket(new GetSubscription($id));
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByConsumer(ChainObject $consumer, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListActiveSubscriptionsByConsumer($consumer, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByAuthor(ChainObject $author, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListActiveSubscriptionsByAuthor($author, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllByConsumer(ChainObject $consumer, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSubscriptionsByConsumer($consumer, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllByAuthor(ChainObject $author, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSubscriptionsByAuthor($author, $count));
    }

    /**
     * @inheritdoc
     */
    public function subscribeToAuthor(
        string $from,
        string $to,
        string $priceAmount,
        string $priceAssetSymbol,
        bool $broadcast = true
    ): BaseOperation
    {
        // TODO: Implement subscribeToAuthor() method.
    }

    /**
     * @inheritdoc
     */
    public function subscribeByAuthor(string $from, string $to, bool $broadcast = true): BaseOperation
    {
        // TODO: Implement subscribeByAuthor() method.
    }

    /**
     * @inheritdoc
     */
    public function setSubscription(
        string $account,
        bool $allowSubscription,
        int $subscriptionPeriod,
        string $priceAmount,
        string $priceAssetSymbol,
        bool $broadcast = true
    ): BaseOperation
    {
        // TODO: Implement setSubscription() method.
    }

    /**
     * @inheritdoc
     */
    public function setAutomaticRenewalOfSubscription(
        string $account,
        string $subscriptionId,
        bool $automaticRenewal,
        bool $broadcast = true
    ): BaseOperation
    {
        // TODO: Implement setAutomaticRenewalOfSubscription() method.
    }
}