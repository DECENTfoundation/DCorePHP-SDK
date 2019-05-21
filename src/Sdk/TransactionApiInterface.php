<?php

namespace DCorePHP\Sdk;

use DCorePHP\DCoreApi;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;

interface TransactionApiInterface
{
    /**
     * create unsigned transaction
     *
     * @param BaseOperation[] operations to include in transaction
     * @param int expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @return Transaction
     */
    public function createTransaction(array $operations, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): Transaction;

    /**
     * @param BaseOperation $operation to include in transaction
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @return Transaction
     */
    public function createTransactionSingleOperation(BaseOperation $operation, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): Transaction;

    /**
     * @param ChainObject $accountId 1.2.*
     * @return mixed a set of proposed transactions
     * TODO: return model
     */
    public function getAllProposed(ChainObject $accountId);

    /**
     * If the transaction has not expired, this method will return the transaction for the given ID or it will return [ch.decent.sdk.exception.ObjectNotFoundException].
     * Just because it is not known does not mean it wasn't included in the DCore. The ID can be retrieved from [Transaction] or [TransactionConfirmation] objects.
     *
     * @param string $trxId transaction id
     * @return ProcessedTransaction if found
     */
    public function getRecent(string $trxId): ProcessedTransaction;

    /**
     * This method will return the transaction for the given ID or it will return [ch.decent.sdk.exception.ObjectNotFoundException].
     * Just because it is not known does not mean it wasn't included in the DCore.
     * The ID can be retrieved from [Transaction] or [TransactionConfirmation] objects.
     * Note: By default these objects are not tracked, the transaction_history_plugin must be loaded for these objects to be maintained.
     *
     * @param string $trxId transaction id
     * @return ProcessedTransaction if found
     */
    public function getById(string $trxId): ProcessedTransaction;

    /**
     * @param string $blockNum block number
     * @param string $trxInBlock position of the transaction in block
     * @return ProcessedTransaction if found
     */
    public function getByBlockNum(string $blockNum, string $trxInBlock): ProcessedTransaction;

    /**
     * get applied transaction
     *
     * @param TransactionConfirmation $confirmation returned from transaction broadcast
     * @return ProcessedTransaction if found
     */
    public function getByConfirmation(TransactionConfirmation $confirmation): ProcessedTransaction;

    /**
     * Get a hexDump of the serialized binary form of a transaction.
     *
     * @param Transaction $transaction signed transaction
     * @return string hexadecimal string
     */
    public function getHexDump(Transaction $transaction): string;

    /**
     * @param array $trxIds
     * @return array
     */
    public function getAll(array $trxIds): array;
}