<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\DCoreApi;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use Exception;
use WebSocket\BadOpcodeException;

interface BroadcastApiInterface
{
    /**
     * Broadcast transaction to DCore
     * @param Transaction $transaction to broadcast
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function broadcast(Transaction $transaction): void;

    /**
     * broadcast operations to DCore
     * @param ECKeyPair $privateKey key
     * @param BaseOperation[] $operations to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws Exception
     */
    public function broadcastOperationsWithECKeyPair(ECKeyPair $privateKey, array $operations, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): void;

    /**
     * broadcast operations to DCore
     * @param ECKeyPair $privateKey key
     * @param BaseOperation $operation to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws Exception
     */
    public function broadcastOperationWithECKeyPair(ECKeyPair $privateKey, BaseOperation $operation, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): void;

    /**
     * broadcast operations to DCore
     * @param string $privateKey
     * @param BaseOperation[] $operations to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws Exception
     */
    public function broadcastOperationsWithPrivateKey(string $privateKey, array $operations, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): void;

    /**
     * broadcast operations to DCore
     * @param string $privateKey
     * @param BaseOperation $operation to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     * @throws Exception
     */
    public function broadcastOperationWithPrivateKey(string $privateKey, BaseOperation $operation, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): void;

    /**
     * broadcast transaction to DCore with callback
     *
     * @param Transaction $transaction to broadcast
     *
     * @return TransactionConfirmation
     *@throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function broadcastWithCallback(Transaction $transaction): ?TransactionConfirmation;

    /**
     * broadcast operations to DCore with callback when applied
     * @param ECKeyPair $privateKey private key
     * @param BaseOperation[] $operations to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     *
     * @throws Exception
     * @return TransactionConfirmation
     */
    public function broadcastOperationsWithECKeyPairWithCallback(ECKeyPair $privateKey, array $operations, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): ?TransactionConfirmation;

    /**
     * broadcast operations to DCore with callback when applied
     * @param ECKeyPair $privateKey private key
     * @param BaseOperation $operation to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     *
     * @throws Exception
     * @return TransactionConfirmation
     */
    public function broadcastOperationWithECKeyPairWithCallback(ECKeyPair $privateKey, BaseOperation $operation, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): ?TransactionConfirmation;

    /**
     * broadcast operations to DCore with callback when applied
     *
     * @param string $privateKey private key
     * @param BaseOperation[] $operations to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     *
     * @return TransactionConfirmation
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function broadcastOperationsWithPrivateKeyWithCallback(string $privateKey, array $operations, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): ?TransactionConfirmation;

    /**
     * broadcast operations to DCore with callback when applied
     *
     * @param string $privateKey private key
     * @param BaseOperation $operation to be submitted to DCore
     * @param int $expiration transaction expiration in seconds, after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     *
     * @return TransactionConfirmation
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function broadcastOperationWithPrivateKeyWithCallback(string $privateKey, BaseOperation $operation, int $expiration = DCoreApi::TRANSACTION_EXPIRATION): ?TransactionConfirmation;

    /**
     * @param Transaction $transaction
     * @return TransactionConfirmation
     */
    public function broadcastSynchronous(Transaction $transaction): TransactionConfirmation;
}