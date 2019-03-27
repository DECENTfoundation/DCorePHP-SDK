<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\GetAccountBalanceForTransaction;
use DCorePHP\Net\Model\Request\GetAccountHistory;
use DCorePHP\Net\Model\Request\GetRelativeAccountHistory;
use DCorePHP\Net\Model\Request\History;
use DCorePHP\Net\Model\Request\SearchAccountBalanceHistory;

class HistoryApi extends BaseApi implements HistoryApiInterface
{
    /**
     * @inheritDoc
     */
    public function getOperation(ChainObject $accountId, ChainObject $operationId): BalanceChange
    {
        return $this->dcoreApi->requestWebsocket(History::class, new GetAccountBalanceForTransaction($accountId, $operationId));
    }

    /**
     * @inheritdoc
     */
    public function listOperations(
        ChainObject $accountId,
        string $startId = '0.0.0',
        string $endId = '0.0.0',
        int $limit = 100
    ): array {
        return $this->dcoreApi->requestWebsocket(History::class, new GetAccountHistory($accountId, $startId, $endId, $limit)) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function listOperationsRelative(ChainObject $accountId, int $start = 0, int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(History::class, new GetRelativeAccountHistory($accountId, 0, $start, $limit));
    }

    /**
     * @inheritDoc
     */
    public function findAllOperations(
        ChainObject $accountId,
        array $assets = [],
        ChainObject $recipientAccount = null,
        string $fromBlock = '0',
        string $toBlock = '0',
        string $startOffset = '0',
        int $limit = 0
    ): array {
        return $this->dcoreApi->requestWebsocket(History::class, new SearchAccountBalanceHistory($accountId, $assets, $recipientAccount, $fromBlock, $toBlock, $startOffset, $limit));
    }

}