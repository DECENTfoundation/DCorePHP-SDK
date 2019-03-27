<?php

namespace DCorePHP\Model;

class AccountBalance
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $owner;
    /** @var ChainObject */
    private $assetType;
    /** @var string */
    private $balance;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return AccountBalance
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): AccountBalance
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
    public function getOwner(): ChainObject
    {
        return $this->owner;
    }

    /**
     * @param ChainObject|string $owner
     * @return AccountBalance
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOwner($owner): AccountBalance
    {
        if (is_string($owner)) {
            $owner = new ChainObject($owner);
        }
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAssetType(): ChainObject
    {
        return $this->assetType;
    }

    /**
     * @param ChainObject|string $assetType
     * @return AccountBalance
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAssetType($assetType): AccountBalance
    {
        if (is_string($assetType)) {
            $assetType = new ChainObject($assetType);
        }
        $this->assetType = $assetType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     * @return AccountBalance
     */
    public function setBalance(string $balance): AccountBalance
    {
        $this->balance = $balance;

        return $this;
    }
}