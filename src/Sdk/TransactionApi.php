<?php

namespace DCorePHP\Sdk;

use DCorePHP\DCoreApi;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetProposedTransactions;
use DCorePHP\Net\Model\Request\GetRecentTransactionById;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\GetTransactionById;
use DCorePHP\Net\Model\Request\GetTransactionHex;

class TransactionApi extends BaseApi implements TransactionApiInterface
{

    /**
     * @inheritdoc
     */
    public function createTransaction(
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): Transaction {
        return $this->dcoreApi->prepareTransaction($operations, $expiration);
    }

    /**
     * @inheritdoc
     */
    public function createTransactionSingleOperation(
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): Transaction {
        return $this->dcoreApi->prepareTransaction([$operation], $expiration);
    }

    /**
     * @inheritdoc
     */
    public function getAllProposed(ChainObject $accountId)
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetProposedTransactions($accountId));
    }

    /**
     * @inheritdoc
     */
    public function getRecent(string $trxId): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetRecentTransactionById($trxId));
    }

    /**
     * @inheritdoc
     */
    public function getById(string $trxId): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetTransactionById($trxId));
    }

    /**
     * @inheritdoc
     */
    public function getByBlockNum(string $blockNum, string $trxInBlock): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetTransaction($blockNum, $trxInBlock));
    }

    /**
     * @inheritdoc
     */
    public function getByConfirmation(TransactionConfirmation $confirmation): ProcessedTransaction
    {
        return $this->getByBlockNum($confirmation->getBlockNum(), $confirmation->getTrxNum());
    }

    /**
     * @inheritdoc
     */
    public function getHexDump(Transaction $transaction): string
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetTransactionHex($transaction));
    }

    /**
     * @inheritdoc
     */
    public function isConfirmed(ChainObject $accountId, ChainObject $transactionId): bool
    {
        $start = $transactionId->getId();
        if ($start !== '1.7.0') {
            $parts = explode('.', $start);
            --$parts[2];
            $start = implode('.', $parts);
        }

        /** @var OperationHistory[] $operationHistories */
        $operationHistories = $this->dcoreApi->getHistoryApi()->listOperations(
            $accountId,
            $start,
            $transactionId
        );

        if (empty($operationHistories)) {
            return false;
        }

        $operationHistory = reset($operationHistories);

        /** @var $dynamicGlobalProperties $dynamicGlobalProps */
        $dynamicGlobalProperties = $this->dcoreApi->getGeneralApi()->getDynamicGlobalProperties();

        return $operationHistory->getBlockNum() <= $dynamicGlobalProperties->getLastIrreversibleBlockNum();
    }
}