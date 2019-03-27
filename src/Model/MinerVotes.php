<?php

namespace DCorePHP\Model;

class MinerVotes
{
    /** @var string */
    private $account;
    /** @var string */
    private $votes;

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param string $account
     * @return MinerVotes
     */
    public function setAccount(string $account): MinerVotes
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getVotes(): string
    {
        return $this->votes;
    }

    /**
     * @param string $votes
     * @return MinerVotes
     */
    public function setVotes(string $votes): MinerVotes
    {
        $this->votes = $votes;

        return $this;
    }
}