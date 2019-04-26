<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class ContentObject
{

    public const REGIONS_NONE_ID = 1;
    public const REGIONS_NULL_ID = 0;
    public const REGIONS_ALL_ID = 2;

    /** @var ChainObject */
    private $id;
    /** @var string */
    private $author;
    /** @var array */
    private $coAuthors;
    /** @var \DateTime */
    private $expiration;
    /** @var string */
    private $created;
    /** @var PricePerRegion */
    private $price;
    /** @var mixed */
    private $synopsis;
    /** @var int */
    private $size;
    /** @var int */
    private $quorum;
    /** @var string */
    private $URI;
    /** @var array */
    private $keyParts;
    /** @var array */
    private $lastProof;
    /** @var array */
    private $seederPrice;
    /** @var boolean */
    private $isBlocked;
    /** @var string */
    private $hash;
    /** @var int */
    private $AVGRating;
    /** @var int */
    private $numOfRatings;
    /** @var int */
    private $timesBought;
    /** @var AssetAmount */
    private $publishingFeeEscrow;
    /** @var string */
    private $status;

    public function __construct()
    {
        $this->price = new PricePerRegion();
        $this->publishingFeeEscrow = new AssetAmount();
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return ContentObject
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): ContentObject
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
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return ContentObject
     */
    public function setAuthor(string $author): ContentObject
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoAuthors(): array
    {
        return $this->coAuthors;
    }

    /**
     * @param array $coAuthors
     * @return ContentObject
     */
    public function setCoAuthors(array $coAuthors): ContentObject
    {
        $this->coAuthors = $coAuthors;

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
     * @return ContentObject
     * @throws \Exception
     */
    public function setExpiration($expiration): ContentObject
    {
        $this->expiration = $expiration instanceof \DateTime ? $expiration : new \DateTime($expiration);

        return $this;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     * @return ContentObject
     */
    public function setCreated(string $created): ContentObject
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return PricePerRegion
     */
    public function getPrice(): PricePerRegion
    {
        return $this->price;
    }

    /**
     * @param PricePerRegion $price
     * @return ContentObject
     */
    public function setPrice(PricePerRegion $price): ContentObject
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
     * @param $synopsis
     * @return ContentObject
     */
    public function setSynopsis($synopsis): ContentObject
    {
        $this->synopsis = $synopsis;

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
     * @return ContentObject
     */
    public function setSize(int $size): ContentObject
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuorum(): int
    {
        return $this->quorum;
    }

    /**
     * @param int $quorum
     * @return ContentObject
     */
    public function setQuorum(int $quorum): ContentObject
    {
        $this->quorum = $quorum;

        return $this;
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->URI;
    }

    /**
     * @param string $URI
     * @return ContentObject
     */
    public function setURI(string $URI): ContentObject
    {
        $this->URI = $URI;

        return $this;
    }

    /**
     * @return array
     */
    public function getKeyParts(): array
    {
        return $this->keyParts;
    }

    /**
     * @param array $keyParts
     * @return ContentObject
     */
    public function setKeyParts(array $keyParts): ContentObject
    {
        $this->keyParts = $keyParts;

        return $this;
    }

    /**
     * @return array
     */
    public function getLastProof(): array
    {
        return $this->lastProof;
    }

    /**
     * @param array $lastProof
     * @return ContentObject
     */
    public function setLastProof(array $lastProof): ContentObject
    {
        $this->lastProof = $lastProof;

        return $this;
    }

    /**
     * @return array
     */
    public function getSeederPrice(): array
    {
        return $this->seederPrice;
    }

    /**
     * @param array $seederPrice
     * @return ContentObject
     */
    public function setSeederPrice(array $seederPrice): ContentObject
    {
        $this->seederPrice = $seederPrice;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     * @return ContentObject
     */
    public function setIsBlocked(bool $isBlocked): ContentObject
    {
        $this->isBlocked = $isBlocked;

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
     * @return ContentObject
     */
    public function setHash(string $hash): ContentObject
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return int
     */
    public function getAVGRating(): int
    {
        return $this->AVGRating;
    }

    /**
     * @param int $AVGRating
     * @return ContentObject
     */
    public function setAVGRating(int $AVGRating): ContentObject
    {
        $this->AVGRating = $AVGRating;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumOfRatings(): int
    {
        return $this->numOfRatings;
    }

    /**
     * @param int $numOfRatings
     * @return ContentObject
     */
    public function setNumOfRatings(int $numOfRatings): ContentObject
    {
        $this->numOfRatings = $numOfRatings;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimesBought(): int
    {
        return $this->timesBought;
    }

    /**
     * @param int $timesBought
     * @return ContentObject
     */
    public function setTimesBought(int $timesBought): ContentObject
    {
        $this->timesBought = $timesBought;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPublishingFeeEscrow(): AssetAmount
    {
        return $this->publishingFeeEscrow;
    }

    /**
     * @param AssetAmount $publishingFeeEscrow
     * @return ContentObject
     */
    public function setPublishingFeeEscrow(AssetAmount $publishingFeeEscrow): ContentObject
    {
        $this->publishingFeeEscrow = $publishingFeeEscrow;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ContentObject
     */
    public function setStatus(string $status): ContentObject
    {
        $this->status = $status;

        return $this;
    }

    public function getPriceNone(): AssetAmount
    {
        return $this->getPrice()->getPrices()[self::REGIONS_ALL_ID];
    }

}