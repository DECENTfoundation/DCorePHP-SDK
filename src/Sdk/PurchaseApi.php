<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Purchase;
use DCorePHP\Model\Operation\LeaveRatingAndComment;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetBuyingByUri;
use DCorePHP\Net\Model\Request\GetHistoryBuyingsByConsumer;
use DCorePHP\Net\Model\Request\GetOpenBuyings;
use DCorePHP\Net\Model\Request\GetOpenBuyingsByConsumer;
use DCorePHP\Net\Model\Request\GetOpenBuyingsByUri;
use DCorePHP\Net\Model\Request\SearchBuyings;
use DCorePHP\Net\Model\Request\SearchFeedback;

class PurchaseApi extends BaseApi implements PurchaseApiInterface
{

    /**
     * @inheritdoc
     */
    public function getAllHistory(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetHistoryBuyingsByConsumer($accountId)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpen(): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetOpenBuyings()) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpenByUri(string $uri): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetOpenBuyingsByUri($uri)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpenByAccount(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetOpenBuyingsByConsumer($accountId)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function get(ChainObject $accountId, string $uri): Purchase
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetBuyingByUri($accountId, $uri));
    }

    /**
     * @inheritdoc
     */
    public function findAll(
        ChainObject $consumer,
        string $term = '',
        string $from = ChainObject::NULL_OBJECT,
        string $order = SearchBuyings::PURCHASED_DESC,
        int $limit = 100
    ): array {
        return $this->dcoreApi->requestWebsocket(Database::class, new SearchBuyings($consumer, $term, $from, $order, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    // TODO: Default arguments: StartId
    public function findAllForFeedback(string $uri, string $user = '', string $startId = ChainObject::NULL_OBJECT, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new SearchFeedback($uri, $user, $startId, $count)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function createRateAndCommentOperation(string $uri, ChainObject $consumer, int $rating, string $comment, AssetAmount $fee = null): LeaveRatingAndComment
    {
        $fee = $fee ?: new AssetAmount();
        $operation = new LeaveRatingAndComment();
        $operation
            ->setUri($uri)
            ->setConsumer($consumer)
            ->setRating($rating)
            ->setComment($comment)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function rateAndComment(Credentials $credentials, string $uri, int $rating, string $comment, AssetAmount $fee = null): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createRateAndCommentOperation($uri, $credentials->getAccount(), $rating, $comment, $fee)
        );
    }
}