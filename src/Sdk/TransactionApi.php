<?php

namespace DCorePHP\Sdk;

use DCorePHP\DCoreApi;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\GetProposedTransactions;
use DCorePHP\Net\Model\Request\GetRecentTransactionById;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\GetTransactionById;
use DCorePHP\Net\Model\Request\GetTransactionHex;
use DCorePHP\Net\Model\Request\GetTransactionsById;

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
        return $this->dcoreApi->requestWebsocket(new GetProposedTransactions($accountId));
    }

    /**
     * @inheritdoc
     */
    public function getRecent(string $trxId): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(new GetRecentTransactionById($trxId));
    }

    /**
     * @inheritdoc
     */
    public function getById(string $trxId): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(new GetTransactionById($trxId));
    }

    /**
     * @inheritdoc
     */
    public function getByBlockNum(string $blockNum, string $trxInBlock): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(new GetTransaction($blockNum, $trxInBlock));
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
        return $this->dcoreApi->requestWebsocket(new GetTransactionHex($transaction));
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $trxIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetTransactionsById($trxIds));
    }
}
