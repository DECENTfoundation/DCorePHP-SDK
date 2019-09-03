<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\DCoreApi;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BroadcastTransaction;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;

class BroadcastApi extends BaseApi implements BroadcastApiInterface
{
    /**
     * @inheritdoc
     */
    public function broadcast(Transaction $transaction): void
    {
        $this->dcoreApi->requestWebsocket(new BroadcastTransaction($transaction));
    }

    /**
     * @inheritdoc
     */
    public function broadcastOperationsWithECKeyPair(
        ECKeyPair $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        $this->broadcastOperationsWithPrivateKey($privateKey->getPrivate()->toWif(), $operations, $expiration);
    }

    /**
     * @inheritdoc
     */
    public function broadcastOperationWithECKeyPair(
        ECKeyPair $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        $this->broadcastOperationWithPrivateKey($privateKey->getPrivate()->toWif(), $operation, $expiration);
    }

    /**
     * @inheritdoc
     */
    public function broadcastOperationsWithPrivateKey(
        string $privateKey,
        array $operations,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        $transaction = $this->dcoreApi->getTransactionApi()->createTransaction($operations, $expiration);
        $transaction->sign($privateKey);

        $this->broadcast($transaction);
    }

    /**
     * @inheritdoc
     */
    public function broadcastOperationWithPrivateKey(
        string $privateKey,
        BaseOperation $operation,
        int $expiration = DCoreApi::TRANSACTION_EXPIRATION
    ): void {
        $transaction = $this->dcoreApi->getTransactionApi()->createTransactionSingleOperation($operation, $expiration);
        $transaction->sign($privateKey);

        $this->broadcast($transaction);
    }

    /**
     * @inheritdoc
     */
    public function broadcastWithCallback(Transaction $transaction): ?TransactionConfirmation
    {
        return $this->dcoreApi->requestWebsocket(new BroadcastTransactionWithCallback($transaction));
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function broadcastSynchronous(Transaction $transaction): TransactionConfirmation
    {
        // TODO: Implement broadcastSynchronous() method.
    }
}