<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Account;
use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\OperationHistoryComposed;
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
        string $startId = '1.7.0',
        string $endId = '1.7.0',
        int $limit = 100
    ): array {
        return $this->dcoreApi->requestWebsocket(History::class, new GetAccountHistory($accountId, $startId, $endId, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function findAllTransfersComposed(
        ChainObject $accountId,
        string $startId = '0.0.0',
        string $endId = '0.0.0',
        int $limit = 10
    ): array {
        // Get transfer operations
        /** @var OperationHistory[] $operations */
        $operations = $this->getAllTransfers($accountId, $startId, $endId, $limit);

        // Get main account
        $account = $this->dcoreApi->getAccountApi()->get($accountId);

        // Get all accounts
        $objectIds = array_map(function (OperationHistory $operation) {return $operation->getOperation()->getTo();}, $operations);
        $accountIds = array_filter($objectIds, function (ChainObject $chainObject) {return explode('.', $chainObject->getId())[0] === '1';});
        $accounts = $this->dcoreApi->getAccountApi()->getAll(array_values(array_unique($accountIds)));
        $accounts = $this->formatObjects($accounts);

        // Get all contents
        $contentIds = array_filter($objectIds, function (ChainObject $chainObject) {return explode('.', $chainObject->getId())[0] === '2';});
        $contents = $this->dcoreApi->getContentApi()->getAll(array_values(array_unique($contentIds)));
        $contents = $this->formatObjects($contents);

        // Merge account and content arrays
        $objects = array_merge($accounts, $contents);

        // Get all assets
        $assetIds = array_map(function (OperationHistory $operation) {return $operation->getOperation()->getAmount()->getAssetId();}, $operations);
        $assets = $this->dcoreApi->getAssetApi()->getAll(array_values(array_unique($assetIds)));
        $assets = $this->formatObjects($assets);

        // Put it all together
        $composedOperations = [];
        foreach ($operations as $operation) {
            // Can't be done in one request
            $trxDetail = $this->dcoreApi->getTransactionApi()->getByBlockNum($operation->getBlockNum(), $operation->getTrxInBlock());
            $composed = new OperationHistoryComposed();
            $composed
                ->setFrom($account)
                ->setTo($objects[$operation->getOperation()->getTo()->getId()])
                ->setOperation($operation->getOperation())
                ->setAsset($assets[$operation->getOperation()->getAmount()->getAssetId()->getId()])
                ->setExpiration($trxDetail->getExpiration());

            $composedOperations[] = $composed;
        }
        return $composedOperations;
    }

    private function formatObjects(array $objects)
    {
        $result = [];
        foreach ($objects as $object) {
            $result[$object->getId()->getId()] = $object;
        }
        return $result;
    }

    private function getAllTransfers(
        ChainObject $accountId,
        string $startId = '0.0.0',
        string $endId = '0.0.0',
        int $limit = 10,
        array $result = []
    ): array {
        /** @var OperationHistory[] $operations */
        $operations = $this->listOperations($accountId, $startId, $endId);
        $operations = array_filter($operations, function (OperationHistory $operation) { return $operation->getOperation() instanceof Transfer2; });
        $operations = array_merge($result, $operations);
        $count = count($operations);

        if ($count >= $limit) {
            return array_slice($operations, 0, $limit);
        } else {
            $newStartId = $operations[$count-1]->getId();
            return $this->getAllTransfers($accountId, $newStartId, $endId, $limit, $operations);
        }
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
        int $limit = 100
    ): array {
        return $this->dcoreApi->requestWebsocket(History::class, new SearchAccountBalanceHistory($accountId, $assets, $recipientAccount, $fromBlock, $toBlock, $startOffset, $limit));
    }

    /**
     * @inheritdoc
     */
    public function isConfirmed(ChainObject $operationId): bool
    {
        $trxs = $this->dcoreApi->getTransactionApi()->getAll([$operationId]);
        /** @var OperationHistory $trx */
        $trx = reset($trxs);

        if (!$trx) {
            return false;
        }

        /** @var DynamicGlobalProps $dynamicGlobalProps */
        $dynamicGlobalProperties = $this->dcoreApi->getGeneralApi()->getDynamicGlobalProperties();

        return $trx->getBlockNum() <= $dynamicGlobalProperties->getLastIrreversibleBlockNum();
    }
}
