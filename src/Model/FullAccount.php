<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Explorer\Miner;

class FullAccount
{
    /** @var Account */
    private $account;
    /** @var AccountStatistics */
    private $statistics;
    /** @var string */
    private $registrarName;
    /** @var Miner[] */
    private $votes;
    /** @var AccountBalance[] */
    private $balances;
    /** @var array */
    private $vestingBalances;
    /** @var array */
    private $proposals;

    public function __construct()
    {
        $this->account = new Account();
        $this->statistics = new AccountStatistics();
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return FullAccount
     */
    public function setAccount(Account $account): FullAccount
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return AccountStatistics
     */
    public function getStatistics(): AccountStatistics
    {
        return $this->statistics;
    }

    /**
     * @param AccountStatistics $statistics
     * @return FullAccount
     */
    public function setStatistics(AccountStatistics $statistics): FullAccount
    {
        $this->statistics = $statistics;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrarName(): string
    {
        return $this->registrarName;
    }

    /**
     * @param string $registrarName
     * @return FullAccount
     */
    public function setRegistrarName(string $registrarName): FullAccount
    {
        $this->registrarName = $registrarName;

        return $this;
    }

    /**
     * @return Miner[]
     */
    public function getVotes(): array
    {
        return $this->votes;
    }

    /**
     * @param Miner[] $votes
     * @return FullAccount
     */
    public function setVotes(array $votes): FullAccount
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @return AccountBalance[]
     */
    public function getBalances(): array
    {
        return $this->balances;
    }

    /**
     * @param AccountBalance[] $balances
     * @return FullAccount
     */
    public function setBalances(array $balances): FullAccount
    {
        $this->balances = $balances;

        return $this;
    }

    /**
     * @return array
     */
    public function getVestingBalances(): array
    {
        return $this->vestingBalances;
    }

    /**
     * @param array $vestingBalances
     * @return FullAccount
     */
    public function setVestingBalances(array $vestingBalances): FullAccount
    {
        $this->vestingBalances = $vestingBalances;

        return $this;
    }

    /**
     * @return array
     */
    public function getProposals(): array
    {
        return $this->proposals;
    }

    /**
     * @param array $proposals
     * @return FullAccount
     */
    public function setProposals(array $proposals): FullAccount
    {
        $this->proposals = $proposals;

        return $this;
    }
}