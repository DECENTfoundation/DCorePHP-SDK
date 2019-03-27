<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class SubscriptionApi extends BaseApi implements SubscriptionApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(ChainObject $id): Subscription
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByConsumer(ChainObject $consumer, int $count = 100): array
    {
        // TODO: Implement listActiveSubscriptionsByConsumer() method.
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByAuthor(ChainObject $author, int $count = 100): array
    {
        // TODO: Implement listActiveSubscriptionsByAuthor() method.
    }

    /**
     * @inheritdoc
     */
    public function getAllByConsumer(ChainObject $consumer, int $count = 100): array
    {
        // TODO: Implement listSubscriptionsByConsumer() method.
    }

    /**
     * @inheritdoc
     */
    public function getAllByAuthor(ChainObject $author, int $count = 100): array
    {
        // TODO: Implement listSubscriptionsByAuthor() method.
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