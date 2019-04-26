<?php


namespace DCorePHP\Model\Content;


use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class Purchase
{

    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $consumer;
    /** @var string */
    private $uri;
    /** @var int */
    private $size;
    /** @var string */
    private $rating;
    /** @var string */
    private $comment;
    /** @var mixed */
    private $synopsis;
    /** @var AssetAmount */
    private $price;
    /** @var AssetAmount */
    private $paidPriceBeforeExchange;
    /** @var AssetAmount */
    private $paidPriceAfterExchange;
    /** @var array */
    private $seedersAnswered;
    /** @var array */
    private $keyParticles;
    /** @var Key */
    private $pubKey;
    /** @var int */
    private $expirationTime;
    /** @var bool */
    private $expired;
    /** @var bool */
    private $delivered;
    /** @var int */
    private $expirationOrDeliveryTime;
    /** @var int */
    private $ratedOrCommented;
    /** @var int */
    private $created;
    /** @var int */
    private $regionCodeFrom;

    public function __construct()
    {
        $this->price = new AssetAmount();
        $this->paidPriceBeforeExchange = new AssetAmount();
        $this->paidPriceAfterExchange = new AssetAmount();
        $this->pubKey = new Key();
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject|string $consumer
     * @return Purchase
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): Purchase
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }
        $this->consumer = $consumer;

        return $this;
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
     * @return Purchase
     */
    public function setUri(string $uri): Purchase
    {
        $this->uri = $uri;

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
     * @return Purchase
     */
    public function setSize(int $size): Purchase
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @param string $rating
     * @return Purchase
     */
    public function setRating(string $rating): Purchase
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Purchase
     */
    public function setComment(string $comment): Purchase
    {
        $this->comment = $comment;

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
     * @return Purchase
     */
    public function setSynopsis($synopsis): Purchase
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return array
     */
    public function getSeedersAnswered(): array
    {
        return $this->seedersAnswered;
    }

    /**
     * @param array $seedersAnswered
     * @return Purchase
     */
    public function setSeedersAnswered(array $seedersAnswered): Purchase
    {
        $this->seedersAnswered = $seedersAnswered;

        return $this;
    }

    /**
     * @return array
     */
    public function getKeyParticles(): array
    {
        return $this->keyParticles;
    }

    /**
     * @param array $keyParticles
     * @return Purchase
     */
    public function setKeyParticles(array $keyParticles): Purchase
    {
        $this->keyParticles = $keyParticles;

        return $this;
    }

    /**
     * @return Key
     */
    public function getPubKey(): Key
    {
        return $this->pubKey;
    }

    /**
     * @param Key $pubKey
     * @return Purchase
     */
    public function setPubKey(Key $pubKey): Purchase
    {
        $this->pubKey = $pubKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     * @return Purchase
     */
    public function setExpirationTime(int $expirationTime): Purchase
    {
        $this->expirationTime = $expirationTime;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired;
    }

    /**
     * @param bool $expired
     * @return Purchase
     */
    public function setExpired(bool $expired): Purchase
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->delivered;
    }

    /**
     * @param bool $delivered
     * @return Purchase
     */
    public function setDelivered(bool $delivered): Purchase
    {
        $this->delivered = $delivered;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationOrDeliveryTime(): int
    {
        return $this->expirationOrDeliveryTime;
    }

    /**
     * @param int $expirationOrDeliveryTime
     * @return Purchase
     */
    public function setExpirationOrDeliveryTime(int $expirationOrDeliveryTime): Purchase
    {
        $this->expirationOrDeliveryTime = $expirationOrDeliveryTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getRatedOrCommented(): int
    {
        return $this->ratedOrCommented;
    }

    /**
     * @param int $ratedOrCommented
     * @return Purchase
     */
    public function setRatedOrCommented(int $ratedOrCommented): Purchase
    {
        $this->ratedOrCommented = $ratedOrCommented;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     * @return Purchase
     */
    public function setCreated(int $created): Purchase
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegionCodeFrom(): int
    {
        return $this->regionCodeFrom;
    }

    /**
     * @param int $regionCodeFrom
     * @return Purchase
     */
    public function setRegionCodeFrom(int $regionCodeFrom): Purchase
    {
        $this->regionCodeFrom = $regionCodeFrom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return Purchase
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): Purchase
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPrice(): AssetAmount
    {
        return $this->price;
    }

    /**
     * @param AssetAmount $price
     * @return Purchase
     */
    public function setPrice(AssetAmount $price): Purchase
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPaidPriceBeforeExchange(): AssetAmount
    {
        return $this->paidPriceBeforeExchange;
    }

    /**
     * @param AssetAmount $paidPriceBeforeExchange
     * @return Purchase
     */
    public function setPaidPriceBeforeExchange(AssetAmount $paidPriceBeforeExchange): Purchase
    {
        $this->paidPriceBeforeExchange = $paidPriceBeforeExchange;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPaidPriceAfterExchange(): AssetAmount
    {
        return $this->paidPriceAfterExchange;
    }

    /**
     * @param AssetAmount $paidPriceAfterExchange
     * @return Purchase
     */
    public function setPaidPriceAfterExchange(AssetAmount $paidPriceAfterExchange): Purchase
    {
        $this->paidPriceAfterExchange = $paidPriceAfterExchange;

        return $this;
    }

}