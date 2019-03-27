<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\OperationType;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;

class ValidationApi extends BaseApi implements ValidationApiInterface
{
    /**
     * @inheritDoc
     */
    public function getRequiredSignatures(Transaction $transaction, array $keys): array
    {
        // TODO: Implement getRequiredSignatures() method.
    }

    /**
     * @inheritDoc
     */
    public function getPotentialSignatures(Transaction $transaction): array
    {
        // TODO: Implement getPotentialSignatures() method.
    }

    /**
     * @inheritDoc
     */
    public function verifyAuthority(Transaction $transaction): bool
    {
        // TODO: Implement verifyAuthority() method.
    }

    /**
     * @inheritDoc
     */
    public function verifyAccountAuthority(string $account, array $keys): bool
    {
        // TODO: Implement verifyAccountAuthority() method.
    }

    /**
     * @inheritDoc
     */
    public function validateTransaction(Transaction $transaction): ProcessedTransaction
    {
        // TODO: Implement validateTransaction() method.
    }

    /**
     * @inheritDoc
     */
    public function getFees(array $op): array
    {
        // TODO: Implement getFees() method.
    }

    /**
     * @inheritDoc
     */
    public function getFee(BaseOperation $op): AssetAmount
    {
        // TODO: Implement getFee() method.
    }

    /**
     * @inheritDoc
     */
    public function getFeeByType(OperationType $type): AssetAmount
    {
        // TODO: Implement getFeeByType() method.
    }
}