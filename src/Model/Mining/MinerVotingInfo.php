<?php

namespace DCorePHP\Model\Mining;

use DCorePHP\Model\ChainObject;

class MinerVotingInfo
{
    /** @var ChainObject */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $url;
    /** @var string */
    private $votes;
    /** @var bool */
    private $voted;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return MinerVotingInfo
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): MinerVotingInfo
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MinerVotingInfo
     */
    public function setName(string $name): MinerVotingInfo
    {
        $this->name = $name;

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
     * @return MinerVotingInfo
     */
    public function setUrl(string $url): MinerVotingInfo
    {
        $this->url = $url;

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
     * @return MinerVotingInfo
     */
    public function setVotes(string $votes): MinerVotingInfo
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVoted(): bool
    {
        return $this->voted;
    }

    /**
     * @param bool $voted
     * @return MinerVotingInfo
     */
    public function setVoted(bool $voted): MinerVotingInfo
    {
        $this->voted = $voted;

        return $this;
    }
}