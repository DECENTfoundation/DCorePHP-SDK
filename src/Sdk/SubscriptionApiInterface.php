<?php


namespace DCorePHP\Sdk;


use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

interface SubscriptionApiInterface
{
    /**
     * Get a subscription object by ID.
     *
     * @param ChainObject $id, 2.15.*
     * @return Subscription corresponding to the provided ID
     */
    public function get(ChainObject $id): Subscription;

    /**
     * Get a list of consumer's active (not expired) subscriptions
     *
     * @param ChainObject $consumer the name or id of the consumer
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     * @return Subscription[] list of active subscription objects corresponding to the provided consumer
     */
    public function getAllActiveByConsumer(ChainObject $consumer, int $count = 100): array;

    /**
     * Get a list of active (not expired) subscriptions to author
     *
     * @param ChainObject $author the name or id of the author
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     * @return Subscription[] list of active subscription objects corresponding to the provided author
     */
    public function getAllActiveByAuthor(ChainObject $author, int $count = 100): array;

    /**
     * Get a list of consumer's subscriptions
     *
     * @param ChainObject $consumer the name or id of the consumer
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     * @return Subscription[] list of subscription objects corresponding to the provided consumer
     */
    public function getAllByConsumer(ChainObject $consumer, int $count = 100): array;

    /**
     * Get a list of subscriptions to author
     *
     * @param ChainObject $author the name or id of the author
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     * @return Subscription[] list of subscription objects corresponding to the provided author
     */
    public function getAllByAuthor(ChainObject $author, int $count = 100): array;

    /**
     * Creates a subscription to author
     * @param string $from account who wants subscription to author
     * @param string $to the author you wish to subscribe to
     * @param string $priceAmount price for the subscription
     * @param string $priceAssetSymbol ticker symbol of the asset which will be used to buy subscription
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction subscribing the consumer to the author
     */
    public function subscribeToAuthor(string $from, string $to, string $priceAmount, string $priceAssetSymbol, bool $broadcast = true): BaseOperation;

    /**
     * Creates a subscription to author
     * @param string $from the account obtaining subscription from the author
     * @param string $to the name or id of the author
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction subscribing the consumer to the author
     */
    public function subscribeByAuthor(string $from, string $to, bool $broadcast = true): BaseOperation;

    /**
     * This function can be used to allow/disallow subscription
     * @param string $account the name or id of the account to update
     * @param bool $allowSubscription true if account (author) wants to allow subscription, false otherwise
     * @param int $subscriptionPeriod duration of subscription in days
     * @param string $priceAmount price for subscription per one subscription period
     * @param string $priceAssetSymbol ticker symbol of the asset which will be used to buy subscription
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction updating the account
     */
    public function setSubscription(string $account, bool $allowSubscription, int $subscriptionPeriod, string $priceAmount, string $priceAssetSymbol, bool $broadcast = true): BaseOperation;

    /**
     * This function can be used to allow/disallow automatic renewal of expired subscription
     * @param string $account the name or id of the account to update
     * @param string $subscriptionId the ID of the subscription
     * @param bool $automaticRenewal true if account (consumer) wants to allow automatic renewal of subscription, false otherwise
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction allowing/disallowing renewal of the subscription
     */
    public function setAutomaticRenewalOfSubscription(string $account, string $subscriptionId, bool $automaticRenewal, bool $broadcast = true): BaseOperation;

}