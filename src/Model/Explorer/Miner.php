<?php

namespace DCorePHP\Model\Explorer;


use DCorePHP\Model\Address;
use DCorePHP\Model\ChainObject;

class Miner
{

    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $minerAccount;
    /** @var int */
    private $lastAslot;
    // TODO
    /** @var Address */
    private $signingKey;
    /** @var ChainObject */
    private $payVb;
    /** @var string */
    private $voteId;
    /** @var int */
    private $totalVotes;
    /** @var string */
    private $url;
    /** @var int */
    private $totalMissed;
    /** @var int */
    private $lastConfirmedBlockNum;
    /** @var int */
    private $voteRanking;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return Miner
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): Miner
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getMinerAccount(): ChainObject
    {
        return $this->minerAccount;
    }

    /**
     * @param ChainObject|string $minerAccount
     * @return Miner
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setMinerAccount($minerAccount): Miner
    {
        if (is_string($minerAccount)) {
            $minerAccount = new ChainObject($minerAccount);
        }
        $this->minerAccount = $minerAccount;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastAslot(): int
    {
        return $this->lastAslot;
    }

    /**
     * @param int $lastAslot
     * @return Miner
     */
    public function setLastAslot(int $lastAslot): Miner
    {
        $this->lastAslot = $lastAslot;

        return $this;
    }

    /**
     * @return string
     */
    public function getSigningKey(): string
    {
        return $this->signingKey;
    }

    /**
     * @param string $signingKey
     * @return Miner
     */
    public function setSigningKey(string $signingKey): Miner
    {
        $this->signingKey = $signingKey;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getPayVb(): ChainObject
    {
        return $this->payVb;
    }

    /**
     * @param ChainObject|string $payVb
     * @return Miner
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setPayVb($payVb): Miner
    {
        if (is_string($payVb)) {
            $payVb = new ChainObject($payVb);
        }
        $this->payVb = $payVb;

        return $this;
    }

    /**
     * @return string
     */
    public function getVoteId(): string
    {
        return $this->voteId;
    }

    /**
     * @param string $voteId
     * @return Miner
     */
    public function setVoteId(string $voteId): Miner
    {
        $this->voteId = $voteId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalVotes(): int
    {
        return $this->totalVotes;
    }

    /**
     * @param int $totalVotes
     * @return Miner
     */
    public function setTotalVotes(int $totalVotes): Miner
    {
        $this->totalVotes = $totalVotes;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Miner
     */
    public function setUrl(string $url): Miner
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalMissed(): int
    {
        return $this->totalMissed;
    }

    /**
     * @param int $totalMissed
     * @return Miner
     */
    public function setTotalMissed(int $totalMissed): Miner
    {
        $this->totalMissed = $totalMissed;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastConfirmedBlockNum(): int
    {
        return $this->lastConfirmedBlockNum;
    }

    /**
     * @param int $lastConfirmedBlockNum
     * @return Miner
     */
    public function setLastConfirmedBlockNum(int $lastConfirmedBlockNum): Miner
    {
        $this->lastConfirmedBlockNum = $lastConfirmedBlockNum;

        return $this;
    }

    /**
     * @return int
     */
    public function getVoteRanking(): int
    {
        return $this->voteRanking;
    }

    /**
     * @param int $voteRanking
     * @return Miner
     */
    public function setVoteRanking(int $voteRanking): Miner
    {
        $this->voteRanking = $voteRanking;

        return $this;
    }

}