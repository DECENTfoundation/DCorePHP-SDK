<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Model\Operation\UpdateAccount;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetActualVotes;
use DCorePHP\Net\Model\Request\GetAssetPerBlock;
use DCorePHP\Net\Model\Request\GetFeedsByMiner;
use DCorePHP\Net\Model\Request\GetMinerByAccount;
use DCorePHP\Net\Model\Request\GetMinerCount;
use DCorePHP\Net\Model\Request\GetMiners;
use DCorePHP\Net\Model\Request\GetNewAssetPerBlock;
use DCorePHP\Net\Model\Request\LookupMinerAccounts;
use DCorePHP\Net\Model\Request\LookupVoteIds;
use DCorePHP\Net\Model\Request\SearchMinerVoting;

class MiningApi extends BaseApi implements MiningApiInterface
{
    /**
     * @inheritDoc
     */
    public function getActualVotes(): array
    {
        return $this->dcoreApi->requestWebsocket(new GetActualVotes());
    }

    /**
     * @inheritDoc
     */
    public function getAssetPerBlock(string $blockNum): string
    {
        return $this->dcoreApi->requestWebsocket(new GetAssetPerBlock($blockNum));
    }

    /**
     * @inheritDoc
     */
    public function getFeedsByMiner(ChainObject $account, int $count = 100)
    {
        return $this->dcoreApi->requestWebsocket(new GetFeedsByMiner($account, $count));
    }

    /**
     * @inheritdoc
     */
    public function getMinerByAccount(ChainObject $account): Miner
    {
        return $this->dcoreApi->requestWebsocket(new GetMinerByAccount($account));
    }

    /**
     * @inheritDoc
     */
    public function getMinerCount(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetMinerCount());
    }

    /**
     * @inheritDoc
     */
    public function getMiners(array $minerIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetMiners($minerIds));
    }

    /**
     * @inheritDoc
     */
    public function getMinersWithName(): array
    {
        $minersWithName = [];
        /** @var MinerId[] $minerIds */
        $minerIds = $this->listMinersRelative();
        /** @var Miner[] $miners */
        $miners = $this->getMiners(array_map(function (MinerId $minerId) {return $minerId->getId(); } ,$minerIds));
        // Loop relies on minerIds and miners arrays to have Ids in the same order
        for ($index = 0, $indexMax = sizeof($minerIds); $index < $indexMax; $index++) {
            $minersWithName[$minerIds[$index]->getName()] = $miners[$index];
        }
        return $minersWithName;
    }

    /**
     * @inheritDoc
     */
    public function getNewAssetPerBlock(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetNewAssetPerBlock());
    }

    /**
     * @inheritDoc
     */
    public function listMinersRelative(string $lowerBound = '', int $limit = 1000): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupMinerAccounts($lowerBound, $limit));
    }

    /**
     * @inheritDoc
     */
    public function findVotedMiners(array $voteIds): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupVoteIds($voteIds));
    }

    /**
     * @inheritDoc
     */
    public function findAllVotingInfo(
        string $searchTerm,
        string $order = SearchMinerVoting::NAME_DESC,
        ?ChainObject $id = null,
        string $accountName = null,
        bool $onlyMyVotes = false,
        int $limit = 1000
    ): array {
        return $this->dcoreApi->requestWebsocket(new SearchMinerVoting($searchTerm, $order, $id, $accountName, $onlyMyVotes, $limit));
    }

    /**
     * @inheritDoc
     */
    public function createVoteOperation(ChainObject $accountId, array $minderIds): UpdateAccount
    {
        $miners = $this->getMiners($minderIds);
        // array_unique used to remove duplicates
        $voteIds = array_unique(array_map(function (Miner $miner) { return $miner->getVoteId(); }, $miners));
        $account = $this->dcoreApi->getAccountApi()->get($accountId);
        $update = new UpdateAccount();
        return $update->setAccountId($account->getId())->setOptions($account->getOptions()->setVotes($voteIds));
    }

    /**
     * @inheritDoc
     */
    public function vote(Credentials $credentials, array $minerIds): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createVoteOperation($credentials->getAccount(), $minerIds)
        );
    }

    /**
     * @inheritdoc
     */
    public function createMiner(string $account, string $url, bool $broadcast = false): BaseOperation
    {
        // TODO: Implement createMiner() method.
    }

    /**
     * @inheritdoc
     */
    public function updateMiner(
        string $minerName,
        string $url,
        string $blockSigningKey,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement updateMiner() method.
    }

    /**
     * @inheritdoc
     */
    public function withdrawVesting(
        string $minerName,
        string $amount,
        string $assetSymbol,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement withdrawVesting() method.
    }

    /**
     * @inheritdoc
     */
    public function voteForMiner(
        string $votingAccount,
        string $miner,
        bool $approve,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement voteForMiner() method.
    }

    /**
     * @inheritdoc
     */
    public function setVotingProxy(
        string $accountToModify,
        string $votingAccount = null,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement setVotingProxy() method.
    }

    /**
     * @inheritdoc
     */
    public function setDesiredMinerCount(
        string $accountToModify,
        int $desiredNumberOfMiners,
        bool $broadcast = true
    ): BaseOperation
    {
        // TODO: Implement setDesiredMinerCount() method.
    }
}