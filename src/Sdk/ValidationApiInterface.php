<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;

interface ValidationApiInterface
{
    /**
     * This API will take a partially signed transaction and a set of public keys that the owner has the ability to sign for
     * and return the minimal subset of public keys that should add signatures to the transaction.
     *
     * @param Transaction $transaction partially signed transaction
     * @param Address[] $keys available owner public keys
     *
     * @return Address[] of public keys that should add signatures
     */
    public function getRequiredSignatures(Transaction $transaction, array $keys): array;

    /**
     * This method will return the set of all public keys that could possibly sign for a given transaction.
     * This call can be used by wallets to filter their set of public keys to just the relevant subset prior to calling get_required_signatures() to get the minimum subset.
     *
     * @param Transaction $transaction unsigned transaction
     *
     * @return Address[] of public keys that can sign transaction
     */
    public function getPotentialSignatures(Transaction $transaction): array;

    /**
     * Verifies required signatures of a transaction.
     *
     * @param Transaction $transaction signed transaction to verify
     *
     * @return bool if the transaction has all of the required signatures
     */
    public function verifyAuthority(Transaction $transaction): bool;

    /**
     * Verifies if the signers have enough authority to authorize an account.
     *
     * @param string $nameOrId name or object id
     * @param Address[] $keys signer keys
     *
     * @return bool if the signers have enough authority
     */
    public function verifyAccountAuthority(string $nameOrId, array $keys): bool;

    /**
     * Validates a transaction against the current state without broadcasting it on the network.
     *
     * @param Transaction $transaction signed transaction
     *
     * @return ProcessedTransaction
     */
    public function validateTransaction(Transaction $transaction): ProcessedTransaction;

    /**
     * Returns fees for operation.
     *
     * @param BaseOperation[] $op of operations
     *
     * @param ChainObject $assetId
     * @return AssetAmount[] of fee asset amounts
     */
    public function getFees(array $op, ChainObject $assetId = null): array;

    /**
     * Returns fee for operation.
     *
     * @param BaseOperation $op
     *
     * @param ChainObject $assetId
     * @return AssetAmount
     */
    public function getFee(BaseOperation $op, ChainObject $assetId = null): AssetAmount;

    /**
     * Returns fee for operation type, not valid for operation per size fees:
     *
     * @param $type
     *
     * @param ChainObject|null $assetId
     * @return AssetAmount
     */
    public function getFeeByType($type, ChainObject $assetId = null): AssetAmount;
}