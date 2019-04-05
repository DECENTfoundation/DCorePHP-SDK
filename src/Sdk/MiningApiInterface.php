<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\MinerVotes;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\Mining\MinerVotingInfo;
use DCorePHP\Model\Operation\UpdateAccount;
use DCorePHP\Net\Model\Request\SearchMinerVoting;

interface MiningApiInterface
{
    /**
     * Get the number of votes each miner actually has.
     *
     * @return MinerVotes[] mapping account names to the number of votes
     */
    public function getActualVotes(): array;

    /**
     * Returns a reward for a miner from a specified block.
     *
     * @param string $blockNum
     * @return string amount of generated DCT
     */
    public function getAssetPerBlock(string $blockNum): string;

    /**
     * Get a list of published price feeds by a miner.
     *
     * @param ChainObject $account, 1.2.*
     * @param int $count maximum number of price feeds to fetch (must not exceed 100)
     * @return mixed a list of price feeds published by the miner
     * TODO: Return model
     */
    public function getFeedsByMiner(ChainObject $account, int $count = 100);

    /**
     * Get the miner owned by a given account.
     *
     * Returns information about the given miner
     * @param ChainObject $accountId the name or id of the miner account owner, or the id of the miner
     * @return Miner the information about the miner stored in the block chain
     */
    public function getMinerByAccount(ChainObject $accountId): Miner;

    /**
     * Get the total number of miners registered in DCore.
     *
     * @return string number of miners
     */
    public function getMinerCount(): string;

    /**
     * Returns list of miners by their Ids
     *
     * @param ChainObject[] $minerIds
     * @return Miner[] of miners
     */
    public function getMiners(array $minerIds): array;

    /**
     * Returns map of the first 1000 miners by their name to miner account
     *
     * @return array of miner name to miner account
     */
    public function getMinersWithName(): array;

    /**
     * Returns a reward for a miner from the most recent block.
     *
     * @return string amount of newly generated DCT
     */
    public function getNewAssetPerBlock(): string;

    /**
     * lookup names and IDs for registered miners
     *
     * @param string $lowerBound of the first name
     * @param int $limit max 1000
     * @return MinerId[] of found miner ids
     */
    public function listMinersRelative(string $lowerBound = '', int $limit = 1000): array;

    /**
     * Given a set of votes, return the objects they are voting for.
     * The results will be in the same order as the votes. null will be returned for any vote ids that are not found.
     *
     * @param array $voteIds set of votes
     * @return array of miners
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
     * @return MinerVotingInfo[] of miner voting info
     */
    public function findAllVotingInfo(string $searchTerm, string $order = SearchMinerVoting::NAME_DESC, ?ChainObject $id = null, string $accountName = null, bool $onlyMyVotes = false, int $limit= 1000): array;

    /**
     * Create vote for miner operation.
     *
     * @param ChainObject $accountId, 1.2.*
     * @param array $minderIds of miner account ids
     * @return UpdateAccount a transaction confirmation
     */
    public function createVoteOperation(ChainObject $accountId, array $minderIds): UpdateAccount;

    /**
     * Creates a miner object owned by the given account.
     * @param string $account the name or id of the account which is creating the miner
     * @param string $url a URL to include in the miner record in the blockchain. Clients may display this when showing a list of miners. May be blank
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction registering a miner
     */
    public function createMiner(string $account, string $url, bool $broadcast = false): BaseOperation;

    /**
     * Update a miner object owned by the given account
     * @param string $minerName The name of the miner's owner account. Also accepts the ID of the owner account or the ID of the miner
     * @param string $url Same as for create_miner. The empty string makes it remain the same
     * @param string $blockSigningKey the new block signing public key. The empty string makes it remain the same
     * @param bool $broadcast true if you wish to broadcast the transaction.
     * @return BaseOperation
     */
    public function updateMiner(string $minerName, string $url, string $blockSigningKey, bool $broadcast = false): BaseOperation;

    /**
     * Withdraw a vesting balance
     * @param string $minerName the account name of the miner, also accepts account ID or vesting balance ID type
     * @param string $amount the amount to withdraw
     * @param string $assetSymbol the symbol of the asset to withdraw
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation
     */
    public function withdrawVesting(string $minerName, string $amount, string $assetSymbol, bool $broadcast = false): BaseOperation;

    /**
     * Vote for a given miner. An account can publish a list of all miners they approve of
     * @param string $votingAccount the name or id of the account who is voting with their shares
     * @param string $miner the name or id of the miner' owner account
     * @param bool $approve true if you wish to vote in favor of that miner, false to remove your vote in favor of that miner
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction changing your vote for the given miner
     */
    public function voteForMiner(string $votingAccount, string $miner, bool $approve, bool $broadcast = false): BaseOperation;

    /**
     * Set the voting proxy for an account. If a user does not wish to take an active part in voting, they can choose to allow another account to vote their stake
     * @param string $accountToModify the name or id of the account to update
     * @param string|null $votingAccount the name or id of an account authorized to vote account_to_modify's shares, or null to vote your own shares
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction changing your vote proxy settings
     */
    public function setVotingProxy(string $accountToModify, string $votingAccount = null, bool $broadcast = false): BaseOperation;

    /**
     * Set your vote for the number of miners in the system
     * @param string $accountToModify the name or id of the account to update
     * @param int $desiredNumberOfMiners
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed transaction changing your vote proxy settings
     */
    public function setDesiredMinerCount(string $accountToModify, int $desiredNumberOfMiners, bool $broadcast = true): BaseOperation;

}