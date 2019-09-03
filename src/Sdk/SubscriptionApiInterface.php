<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Subscription\Subscription;
use WebSocket\BadOpcodeException;

interface SubscriptionApiInterface
{
    /**
     * Get a subscription object by ID.
     *
     * @param ChainObject $id, 2.15.*
     *
     * @return Subscription corresponding to the provided ID
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function get(ChainObject $id): Subscription;

    /**
     * Get a list of consumer's active (not expired) subscriptions
     *
     * @param ChainObject $consumer the name or id of the consumer
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     *
     * @return Subscription[] list of active subscription objects corresponding to the provided consumer
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllActiveByConsumer(ChainObject $consumer, int $count = 100): array;

    /**
     * Get a list of active (not expired) subscriptions to author
     *
     * @param ChainObject $author the name or id of the author
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     *
     * @return Subscription[] list of active subscription objects corresponding to the provided author
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllActiveByAuthor(ChainObject $author, int $count = 100): array;

    /**
     * Get a list of consumer's subscriptions
     *
     * @param ChainObject $consumer the name or id of the consumer
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     *
     * @return Subscription[] list of subscription objects corresponding to the provided consumer
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllByConsumer(ChainObject $consumer, int $count = 100): array;

    /**
     * Get a list of subscriptions to author
     *
     * @param ChainObject $author the name or id of the author
     * @param int $count maximum number of subscriptions to fetch (must not exceed 100)
     *
     * @return Subscription[] list of subscription objects corresponding to the provided author
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllByAuthor(ChainObject $author, int $count = 100): array;
}