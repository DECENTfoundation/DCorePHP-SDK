<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\KeyParts;
use DCorePHP\Model\RegionalPrice;

class SubmitContent
{
    /** @var string */
    private $uri;
    /** @var \DateTime */
    private $expiration;
    /** @var RegionalPrice[] */
    private $price;
    /** @var mixed */
    private $synopsis;
    /** @var string */
    private $hash;
    /** @var string */
    private $quorum;
    /** @var int */
    private $size;
    /** @var ChainObject[] */
    private $seeders;
    /** @var KeyParts[] */
    private $keyParts;
    /** @var array */
    private $coauthors;
    /** @var CustodyData */
    private $custodyData;

    public function __construct()
    {
        $this->price = array();
        $this->seeders = array();
        $this->custodyData = new CustodyData();
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return SubmitContent
     */
    public function setUri(string $uri): SubmitContent
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|string $expiration
     * @return SubmitContent
     * @throws \Exception
     */
    public function setExpiration($expiration): SubmitContent
    {
        $this->expiration = $expiration instanceof \DateTime ? $expiration : new \DateTime($expiration);

        return $this;
    }

    /**
     * @return RegionalPrice[]
     */
    public function getPrice(): array
    {
        return $this->price;
    }

    /**
     * @param RegionalPrice[] $price
     * @return SubmitContent
     */
    public function setPrice(array $price): SubmitContent
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * @return mixed
     */
    public function getSynopsisDecoded()
    {
        return json_decode($this->synopsis, true) ?? $this->synopsis;
    }

    /**
     * @param mixed $synopsis
     * @return SubmitContent
     */
    public function setSynopsis($synopsis): SubmitContent
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return SubmitContent
     */
    public function setHash(string $hash): SubmitContent
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuorum(): string
    {
        return $this->quorum;
    }

    /**
     * @param string $quorum
     * @return SubmitContent
     */
    public function setQuorum(string $quorum): SubmitContent
    {
        $this->quorum = $quorum;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return SubmitContent
     */
    public function setSize(int $size): SubmitContent
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return ChainObject[]
     */
    public function getSeeders(): array
    {
        return $this->seeders;
    }

    /**
     * @param ChainObject[] $seeders
     * @return SubmitContent
     */
    public function setSeeders(array $seeders): SubmitContent
    {
        $this->seeders = $seeders;

        return $this;
    }

    /**
     * @return KeyParts[]
     */
    public function getKeyParts(): array
    {
        return $this->keyParts;
    }

    /**
     * @param KeyParts[] $keyParts
     * @return SubmitContent
     */
    public function setKeyParts(array $keyParts): SubmitContent
    {
        $this->keyParts = $keyParts;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoauthors(): array
    {
        return $this->coauthors;
    }

    /**
     * @param array $coauthors
     * @return SubmitContent
     */
    public function setCoauthors(array $coauthors): SubmitContent
    {
        $this->coauthors = $coauthors;

        return $this;
    }

    /**
     * @return CustodyData
     */
    public function getCustodyData(): ?CustodyData
    {
        return $this->custodyData;
    }

    /**
     * @param CustodyData $custodyData
     * @return SubmitContent
     */
    public function setCustodyData(?CustodyData $custodyData): SubmitContent
    {
        $this->custodyData = $custodyData;

        return $this;
    }
}