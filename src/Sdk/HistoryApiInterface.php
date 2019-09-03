<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\OperationHistory;
use WebSocket\BadOpcodeException;

interface HistoryApiInterface
{

    /**
     * Returns balance operation on the account and operation id.
     *
     * @param ChainObject $accountId of the account whose history should be queried, 1.2.*
     * @param ChainObject $operationId of the history object, 1.7.*
     * @return BalanceChange
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function getOperation(ChainObject $accountId, ChainObject $operationId): BalanceChange;

    /**
     * Returns the most recent operations on the named account. This returns a list of operation history objects, which describe activity on the account.
     *
     * @param ChainObject $accountId the name or id of the account
     * @param string $startId object_id to start searching from
     * @param string $endId object_id to start searching to
     * @param int $limit the number of entries to return (starting from the most recent)
     * @return OperationHistory[]
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listOperations(ChainObject $accountId, string $startId = '0.0.0', string $endId = '0.0.0', int $limit = 100): array;

    /**
     * @param ChainObject $accountId
     * @param string $startId
     * @param string $endId
     * @param int $limit
     *
     * @return array
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws BadOpcodeException
     */
    public function findAllTransfersComposed(ChainObject $accountId, string $startId = '0.0.0', string $endId = '0.0.0', int $limit = 10): array;

    /**
     * Get account history of operations.
     *
     * @param ChainObject $accountId of the account whose history should be queried, 1.2.*
     * @param int $start sequence number of the most recent operation to retrieve. 0 is default, which will start querying from the most recent operation
     * @param int $limit maximum number of operations to retrieve (must not exceed 100)
     *
     * @return OperationHistory[] of operations performed by account, ordered from most recent to oldest
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listOperationsRelative(ChainObject $accountId, int $start = 0, int $limit = 100): array;

    /**
     * Returns the most recent balance operations on the named account.
     * This returns a list of operation history objects, which describe activity on the account.
     *
     * @param ChainObject $accountId of the account whose history should be queried, 1.2.*
     * @param Asset[] $assets of asset object ids to filter or empty for all assets
     * @param ChainObject|null $recipientAccount partner account object id to filter transfers to specific account, 1.2.* or null
     * @param string $fromBlock filtering parameter, starting block number (can be determined from time) or zero when not used
     * @param string $toBlock filtering parameter, ending block number or zero when not used
     * @param string $startOffset starting offset from zero
     * @param int $limit the number of entries to return (starting from the most recent), max 100
     *
     * @return BalanceChange[] of balance changes
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllOperations(ChainObject $accountId, array $assets = [], ChainObject $recipientAccount = null, string $fromBlock = '0', string $toBlock = '0', string $startOffset = '0', int $limit = 100): array;

    /**
     * Verifies if block in that transaction was processed to is irreversible.
     * NOTE: Unverified blocks still can be reversed.
     *
     * NOTICE:
     * History object with id in form '1.7.X' can be fetched from AccountApi->getAccountHistory() method.
     *
     * @param ChainObject $operationId
     * @return bool Returns true if transaction is in irreversible block, false otherwise.
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function isConfirmed(ChainObject $operationId): bool;
}