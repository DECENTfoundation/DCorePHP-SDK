<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\Operation\AccountUpdateOperation;
use DCorePHP\Model\TransactionConfirmation;
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
     * @inheritdoc
     */
    public function getActualVotes(): array
    {
        return $this->dcoreApi->requestWebsocket(new GetActualVotes());
    }

    /**
     * @inheritdoc
     */
    public function getAssetPerBlock(string $blockNum): string
    {
        return $this->dcoreApi->requestWebsocket(new GetAssetPerBlock($blockNum));
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getMinerCount(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetMinerCount());
    }

    /**
     * @inheritdoc
     */
    public function getMiners(array $minerIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetMiners($minerIds));
    }

    /**
     * @inheritdoc
     */
    public function getMinersWithName(): array
    {
        $minersWithName = [];
        /** @var MinerId[] $minerIds */
        $minerIds = $this->listMinersRelative();
        /** @var Miner[] $miners */
        $miners = $this->getMiners(array_map(static function (MinerId $minerId) {return $minerId->getId(); } ,$minerIds));
        // Loop relies on minerIds and miners arrays to have Ids in the same order
        for ($index = 0, $indexMax = sizeof($minerIds); $index < $indexMax; $index++) {
            $minersWithName[$minerIds[$index]->getName()] = $miners[$index];
        }
        return $minersWithName;
    }

    /**
     * @inheritdoc
     */
    public function getNewAssetPerBlock(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetNewAssetPerBlock());
    }

    /**
     * @inheritdoc
     */
    public function listMinersRelative(string $lowerBound = '', int $limit = 1000): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupMinerAccounts($lowerBound, $limit));
    }

    /**
     * @inheritdoc
     */
    public function findVotedMiners(array $voteIds): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupVoteIds($voteIds));
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function createVoteOperation(ChainObject $accountId, array $minderIds, $fee = null): AccountUpdateOperation
    {
        $miners = $this->getMiners($minderIds);
        // array_unique used to remove duplicates
        $voteIds = array_unique(array_map(static function (Miner $miner) { return $miner->getVoteId(); }, $miners));
        $account = $this->dcoreApi->getAccountApi()->get($accountId);
        $operation = new AccountUpdateOperation();
        $operation
            ->setAccountId($account->getId())
            ->setOptions($account->getOptions()->setVotes($voteIds));
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function vote(Credentials $credentials, array $minerIds, $fee = null): ?TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createVoteOperation($credentials->getAccount(), $minerIds, $fee)
        );
    }
}