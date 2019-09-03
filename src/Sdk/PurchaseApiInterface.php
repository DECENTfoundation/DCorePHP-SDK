<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Purchase;
use DCorePHP\Model\Operation\LeaveRatingAndComment;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\SearchBuyings;
use Exception;
use WebSocket\BadOpcodeException;

interface PurchaseApiInterface
{

    /**
     * Get history buyings by consumer
     * @param ChainObject $accountId
     *
     * @return Purchase[] list of history buying objects corresponding to the provided consumer
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllHistory(ChainObject $accountId): array;

    /**
     * Get a list of open buyings
     *
     * @return Purchase[] list of open buying objects
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllOpen(): array;

    /**
     * Get a list of open buyings by URI
     * @param string $uri URI of the buyings to retrieve
     *
     * @return Purchase[] buying objects corresponding to the provided consumer, or null if no matching buying was found
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllOpenByUri(string $uri): array;

    /**
     * @param ChainObject $accountId
     *
     * @return Purchase[] buying objects corresponding to the provided consumer, or null if no matching buying was found
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllOpenByAccount(ChainObject $accountId): array;


    /**
     * Get buying object (open or history) by consumer and URI
     * @param ChainObject $accountId
     * @param string $uri the URI of the buying to retrieve
     *
     * @return Purchase buying objects corresponding to the provided consumer, or null if no matching buying was found
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function get(ChainObject $accountId, string $uri): Purchase;

    /**
     * Get history buying objects by consumer that match search term
     * @param ChainObject $consumer
     * @param string $term search term to look up in title and description
     * @param string $from
     * @param string $order sort data by field
     * @param int $limit maximum number of contents to fetch (must not exceed 100)
     *
     * @return Purchase[] list of history buying objects corresponding to the provided consumer and matching search term
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAll(ChainObject $consumer, string $term = '', string $from = ChainObject::NULL_OBJECT, string $order = SearchBuyings::PURCHASED_DESC, int $limit = 100): array;

    /**
     * Search for term in user's feedbacks
     * @param string $uri the content object URI
     * @param string $user the author of the feedback
     * @param string $startId
     * @param int $count maximum number of feedbacks to fetch
     *
     * @return Purchase[] the feedback found
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllForFeedback(string $uri, string $user = '', string $startId = ChainObject::NULL_OBJECT, int $count = 100): array;

    /**
     * Create a rate and comment content operation
     *
     * @param string $uri
     * @param ChainObject $consumer
     * @param int $rating
     * @param string $comment
     * @param AssetAmount|null $fee
     *
     * @return LeaveRatingAndComment
     *
     * @throws ValidationException
     */
    public function createRateAndCommentOperation(string $uri, ChainObject $consumer, int $rating, string $comment, AssetAmount $fee = null): LeaveRatingAndComment;

    /**
     * Rate and comment content operation
     *
     * @param Credentials $credentials
     * @param string $uri
     * @param int $rating
     * @param string $comment
     * @param AssetAmount|null $fee
     *
     * @return TransactionConfirmation|null
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function rateAndComment(Credentials $credentials, string $uri, int $rating, string $comment, AssetAmount $fee = null): ?TransactionConfirmation;

}