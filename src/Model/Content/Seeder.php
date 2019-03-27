<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class Seeder
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $seeder;
    /** @var int */
    private $freeSpace;
    /** @var string */
    private $expiration;
    /** @var Key */
    private $pubKey;
    /** @var string */
    private $ipfsId;
    /** @var string */
    private $stats;
    /** @var int */
    private $rating;
    /** @var string */
    private $regionCode;
    /** @var AssetAmount */
    private $price;

    public function __construct()
    {
        $this->price = new AssetAmount();
        $this->pubKey = new Key();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return Seeder
     */
    public function setId($id): Seeder
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
    public function getSeeder(): ChainObject
    {
        return $this->seeder;
    }

    /**
     * @param ChainObject | string $seederId
     * @return Seeder
     */
    public function setSeeder($seederId): Seeder
    {
        if (is_string($seederId)) {
            $seederId = new ChainObject($seederId);
        }
        $this->seeder = $seederId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFreeSpace(): int
    {
        return $this->freeSpace;
    }

    /**
     * @param int $freeSpace
     * @return Seeder
     */
    public function setFreeSpace(int $freeSpace): Seeder
    {
        $this->freeSpace = $freeSpace;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpiration(): string
    {
        return $this->expiration;
    }

    /**
     * @param string $expiration
     * @return Seeder
     */
    public function setExpiration(string $expiration): Seeder
    {
        $this->expiration = $expiration;

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
     * @return Seeder
     */
    public function setPubKey(Key $pubKey): Seeder
    {
        $this->pubKey = $pubKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getIpfsId(): string
    {
        return $this->ipfsId;
    }

    /**
     * @param string $ipfsId
     * @return Seeder
     */
    public function setIpfsId(string $ipfsId): Seeder
    {
        $this->ipfsId = $ipfsId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStats(): string
    {
        return $this->stats;
    }

    /**
     * @param string $stats
     * @return Seeder
     */
    public function setStats(string $stats): Seeder
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return Seeder
     */
    public function setRating(int $rating): Seeder
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     * @return Seeder
     */
    public function setRegionCode(string $regionCode): Seeder
    {
        $this->regionCode = $regionCode;

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
     * @return Seeder
     */
    public function setPrice(AssetAmount $price): Seeder
    {
        $this->price = $price;

        return $this;
    }

}