<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use WebSocket\BadOpcodeException;

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
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getRequiredSignatures(Transaction $transaction, array $keys): array;

    /**
     * This method will return the set of all public keys that could possibly sign for a given transaction.
     * This call can be used by wallets to filter their set of public keys to just the relevant subset prior to calling get_required_signatures() to get the minimum subset.
     *
     * @param Transaction $transaction unsigned transaction
     *
     * @return Address[] of public keys that can sign transaction
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getPotentialSignatures(Transaction $transaction): array;

    /**
     * Verifies required signatures of a transaction.
     *
     * @param Transaction $transaction signed transaction to verify
     *
     * @return bool if the transaction has all of the required signatures
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function verifyAuthority(Transaction $transaction): bool;

    /**
     * Verifies if the signers have enough authority to authorize an account.
     *
     * @param string $nameOrId name or object id
     * @param Address[] $keys signer keys
     *
     * @return bool if the signers have enough authority
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function verifyAccountAuthority(string $nameOrId, array $keys): bool;

    /**
     * Validates a transaction against the current state without broadcasting it on the network.
     *
     * @param Transaction $transaction signed transaction
     *
     * @return ProcessedTransaction
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function validateTransaction(Transaction $transaction): ProcessedTransaction;

    /**
     * Returns fees for operation.
     *
     * @param BaseOperation[] $op of operations
     * @param ChainObject $assetId
     *
     * @return AssetAmount[] of fee asset amounts
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getFees(array $op, ChainObject $assetId = null): array;

    /**
     * Returns fee for operation.
     *
     * @param BaseOperation $op
     * @param ChainObject $assetId
     *
     * @return AssetAmount
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getFee(BaseOperation $op, ChainObject $assetId = null): AssetAmount;

    /**
     * Returns fees for operation type, not valid for operation per size fees:
     * AssetCreateOperation
     * AssetIssueOperation
     * ProposalCreate
     * ProposalUpdate
     * WithdrawPermissionClaim
     * CustomOperation
     * AssertOperation
     * AddOrUpdateContentOperation
     *
     * @param array $types
     * @param ChainObject|null $assetId
     *
     * @return AssetAmount[]
     *
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function getFeesForType(array $types, ChainObject$assetId = null): array;

    /**
     * Returns fee for operation type, not valid for operation per size fees:
     * AssetCreateOperation
     * AssetIssueOperation
     * ProposalCreate
     * ProposalUpdate
     * WithdrawPermissionClaim
     * CustomOperation
     * AssertOperation
     * AddOrUpdateContentOperation
     *
     * @param $type
     * @param ChainObject|null $assetId
     *
     * @return AssetAmount
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ValidationException
     */
    public function getFeeForType($type, ChainObject $assetId = null): AssetAmount;
}