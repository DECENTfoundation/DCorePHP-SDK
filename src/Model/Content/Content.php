<?php

namespace DCorePHP\Model\Content;

use DateTime;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\RegionalPrice;
use Exception;

class Content
{

    public const REGIONS_NONE_ID = 1;
    public const REGIONS_NULL_ID = 0;
    public const REGIONS_ALL_ID = 2;

    /** @var ChainObject */
    private $id;
    /** @var string */
    private $author;
    /** @var CoAuthors */
    private $coAuthors;
    /** @var DateTime */
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
    /** @var CustodyData */
    private $custodyData;

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
     *
     * @return Content
     * @throws ValidationException
     */
    public function setId($id): Content
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
     *
     * @return Content
     */
    public function setAuthor(string $author): Content
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return CoAuthors
     */
    public function getCoAuthors(): CoAuthors
    {
        return $this->coAuthors;
    }

    /**
     * @param CoAuthors $coAuthors
     *
     * @return Content
     */
    public function setCoAuthors(CoAuthors $coAuthors): Content
    {
        $this->coAuthors = $coAuthors;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiration(): DateTime
    {
        return $this->expiration;
    }

    /**
     * @param DateTime|string $expiration
     *
     * @return Content
     * @throws Exception
     */
    public function setExpiration($expiration): Content
    {
        $this->expiration = $expiration instanceof DateTime ? $expiration : new DateTime($expiration);

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
     *
     * @return Content
     */
    public function setCreated(string $created): Content
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
     *
     * @return Content
     */
    public function setPrice(PricePerRegion $price): Content
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
     *
     * @return Content
     */
    public function setSynopsis($synopsis): Content
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
     *
     * @return Content
     */
    public function setSize(int $size): Content
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
     *
     * @return Content
     */
    public function setQuorum(int $quorum): Content
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
     *
     * @return Content
     */
    public function setURI(string $URI): Content
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
     *
     * @return Content
     */
    public function setKeyParts(array $keyParts): Content
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
     *
     * @return Content
     */
    public function setLastProof(array $lastProof): Content
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
     *
     * @return Content
     */
    public function setSeederPrice(array $seederPrice): Content
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
     *
     * @return Content
     */
    public function setIsBlocked(bool $isBlocked): Content
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
     *
     * @return Content
     */
    public function setHash(string $hash): Content
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
     *
     * @return Content
     */
    public function setAVGRating(int $AVGRating): Content
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
     *
     * @return Content
     */
    public function setNumOfRatings(int $numOfRatings): Content
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
     *
     * @return Content
     */
    public function setTimesBought(int $timesBought): Content
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
     *
     * @return Content
     */
    public function setPublishingFeeEscrow(AssetAmount $publishingFeeEscrow): Content
    {
        $this->publishingFeeEscrow = $publishingFeeEscrow;

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
     *
     * @return Content
     */
    public function setCustodyData(?CustodyData $custodyData): Content
    {
        $this->custodyData = $custodyData;

        return $this;
    }

    public function regionalPrice(int $forRegion = null): RegionalPrice
    {
        $rp = new RegionalPrice();
        if ($forRegion) {
            return $rp->setRegion($forRegion)->setPrice($this->getPrice()->getPrices()[$forRegion]);
        }

        $prices = $this->getPrice()->getPrices();
        $price = reset($prices);
        $region = array_keys($prices)[0];

        return $rp->setRegion($region)->setPrice($price);
    }

    public static function hasValid($reference): bool
    {
        return preg_match ( '/^(https?|ipfs|magnet):/', $reference);
    }
}