<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\DCoreApi;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\NetworkBroadcast;

class BroadcastApi extends BaseApi implements BroadcastApiInterface
{
    /**
     * @inheritDoc
     */
    public function broadcast(Transaction $transaction): void
    {
        // TODO: Implement broadcast() method.
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationsWithECKeyPair(
        ECKeyPair $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        // TODO: Implement broadcastOperationsWithECKeyPair() method.
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationWithECKeyPair(
        ECKeyPair $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        // TODO: Implement broadcastOperationWithECKeyPair() method.
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationsWithPrivateKey(
        string $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        // TODO: Implement broadcastOperationsWithPrivateKey() method.
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationWithPrivateKey(
        string $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        // TODO: Implement broadcastOperationWithPrivateKey() method.
    }

    /**
     * @inheritDoc
     */
    public function broadcastWithCallback(Transaction $transaction): ?TransactionConfirmation
    {
        return $this->dcoreApi->requestWebsocket(NetworkBroadcast::class, new BroadcastTransactionWithCallback($transaction));
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationsWithECKeyPairWithCallback(
        ECKeyPair $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): ?TransactionConfirmation {
        return $this->broadcastOperationsWithPrivateKeyWithCallback(
            $privateKey->getPrivate()->toWif(),
            $operations,
            $expiration
        );
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationWithECKeyPairWithCallback(
        ECKeyPair $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): ?TransactionConfirmation {
        return $this->broadcastOperationWithPrivateKeyWithCallback(
            $privateKey->getPrivate()->toWif(),
            $operation,
            $expiration
        );
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationsWithPrivateKeyWithCallback(
        string $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): ?TransactionConfirmation {
        $transaction = $this->dcoreApi->getTransactionApi()->createTransaction($operations, $expiration);
        $transaction->sign($privateKey);

        return $this->broadcastWithCallback($transaction);
    }

    /**
     * @inheritDoc
     */
    public function broadcastOperationWithPrivateKeyWithCallback(
        string $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): ?TransactionConfirmation {
        $transaction = $this->dcoreApi->getTransactionApi()->createTransactionSingleOperation($operation, $expiration);
        $transaction->sign($privateKey);

        return $this->broadcastWithCallback($transaction);
    }

    /**
     * @inheritDoc
     */
    public function broadcastSynchronous(Transaction $transaction): TransactionConfirmation
    {
        // TODO: Implement broadcastSynchronous() method.
    }
}