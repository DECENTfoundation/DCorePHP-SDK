<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class Content
{

    /** @var ChainObject */
    private $id;
    /** @var string */
    private $author;
    /** @var int */
    private $expiration;
    /** @var string */
    private $created;
    /** @var AssetAmount */
    private $price;
    /** @var mixed */
    private $synopsis;
    /** @var int */
    private $size;
    /** @var string */
    private $URI;
    /** @var string */
    private $hash;
    /** @var int */
    private $AVGRating;
    /** @var int */
    private $timesBought;
    /** @var string */
    private $status;

    public function __construct()
    {
        $this->price = new AssetAmount();
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
     * @return Content
     * @throws \DCorePHP\Exception\ValidationException
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
     * @return Content
     */
    public function setAuthor(string $author): Content
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * @param int $expiration
     * @return Content
     */
    public function setExpiration(int $expiration): Content
    {
        $this->expiration = $expiration;

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
     * @return Content
     */
    public function setCreated(string $created): Content
    {
        $this->created = $created;

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
     * @return Content
     */
    public function setPrice(AssetAmount $price): Content
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
     * @return Content
     */
    public function setSize(int $size): Content
    {
        $this->size = $size;

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
     * @return Content
     */
    public function setURI(string $URI): Content
    {
        $this->URI = $URI;

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
    public function getTimesBought(): int
    {
        return $this->timesBought;
    }

    /**
     * @param int $timesBought
     * @return Content
     */
    public function setTimesBought(int $timesBought): Content
    {
        $this->timesBought = $timesBought;

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
     * @return Content
     */
    public function setStatus(string $status): Content
    {
        $this->status = $status;

        return $this;
    }

}