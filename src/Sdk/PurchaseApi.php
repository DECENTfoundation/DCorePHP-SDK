<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Purchase;
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
        return $this->dcoreApi->requestWebsocket(new GetHistoryBuyingsByConsumer($accountId)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpen(): array
    {
        return $this->dcoreApi->requestWebsocket(new GetOpenBuyings()) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpenByUri(string $uri): array
    {
        return $this->dcoreApi->requestWebsocket(new GetOpenBuyingsByUri($uri)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getAllOpenByAccount(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(new GetOpenBuyingsByConsumer($accountId)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function get(ChainObject $accountId, string $uri): Purchase
    {
        return $this->dcoreApi->requestWebsocket(new GetBuyingByUri($accountId, $uri));
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
        return $this->dcoreApi->requestWebsocket(new SearchBuyings($consumer, $term, $from, $order, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    // TODO: Default arguments: StartId
    public function findAllForFeedback(string $uri, string $user = '', string $startId = ChainObject::NULL_OBJECT, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new SearchFeedback($uri, $user, $startId, $count)) ?: [];
    }
}