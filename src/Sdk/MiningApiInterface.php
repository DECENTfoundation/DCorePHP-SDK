<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\MinerVotes;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\Mining\MinerVotingInfo;
use DCorePHP\Model\Operation\AccountUpdateOperation;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\SearchMinerVoting;
use Exception;
use WebSocket\BadOpcodeException;

interface MiningApiInterface
{
    /**
     * Get the number of votes each miner actually has.
     *
     * @return MinerVotes[] mapping account names to the number of votes
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getActualVotes(): array;

    /**
     * Returns a reward for a miner from a specified block.
     *
     * @param string $blockNum
     *
     * @return string amount of generated DCT
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAssetPerBlock(string $blockNum): string;

    /**
     * Get a list of published price feeds by a miner.
     *
     * @param ChainObject $account, 1.2.*
     * @param int $count maximum number of price feeds to fetch (must not exceed 100)
     *
     * TODO: Return model
     * @return mixed a list of price feeds published by the miner
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getFeedsByMiner(ChainObject $account, int $count = 100);

    /**
     * Get the miner owned by a given account.
     *
     * Returns information about the given miner
     * @param ChainObject $accountId the name or id of the miner account owner, or the id of the miner
     *
     * @return Miner the information about the miner stored in the block chain
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getMinerByAccount(ChainObject $accountId): Miner;

    /**
     * Get the total number of miners registered in DCore.
     *
     * @return string number of miners
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getMinerCount(): string;

    /**
     * Returns list of miners by their Ids
     *
     * @param ChainObject[] $minerIds
     *
     * @return Miner[] of miners
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getMiners(array $minerIds): array;

    /**
     * Returns map of the first 1000 miners by their name to miner account
     *
     * @return array of miner name to miner account
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getMinersWithName(): array;

    /**
     * Returns a reward for a miner from the most recent block.
     *
     * @return string amount of newly generated DCT
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getNewAssetPerBlock(): string;

    /**
     * lookup names and IDs for registered miners
     *
     * @param string $lowerBound of the first name
     * @param int $limit max 1000
     *
     * @return MinerId[] of found miner ids
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listMinersRelative(string $lowerBound = '', int $limit = 1000): array;

    /**
     * Given a set of votes, return the objects they are voting for.
     * The results will be in the same order as the votes. null will be returned for any vote ids that are not found.
     *
     * @param array $voteIds set of votes
     *
     * @return array of miners
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findVotedMiners(array $voteIds): array;

    /**
     * Get miner voting info list by account that match search term.
     *
     * @param string $searchTerm miner name
     * @param string $order
     * @param ChainObject|null $id the object id of the miner to start searching from, 1.4.* or null when start from beginning
     * @param string|null $accountName account name or null when searching without account
     * @param bool $onlyMyVotes when true it selects only votes given by account
     * @param int $limit maximum number of miners info to fetch (must not exceed 1000)
     *
     * @return MinerVotingInfo[] of miner voting info
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllVotingInfo(string $searchTerm, string $order = SearchMinerVoting::NAME_DESC, ?ChainObject $id = null, string $accountName = null, bool $onlyMyVotes = false, int $limit= 1000): array;

    /**
     * Create vote for miner operation.
     *
     * @param ChainObject $accountId, 1.2.*
     * @param array $minderIds of miner account ids
     * @param null $fee
     *
     * @return AccountUpdateOperation a transaction confirmation
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createVoteOperation(ChainObject $accountId, array $minderIds, $fee = null): AccountUpdateOperation;

    /**
     * Create vote for miner operation
     *
     * @param Credentials $credentials
     * @param array $minerIds
     * @param null $fee
     *
     * @return TransactionConfirmation|null
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function vote(Credentials $credentials, array $minerIds, $fee = null): ?TransactionConfirmation;
}