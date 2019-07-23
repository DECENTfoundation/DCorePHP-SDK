<?php

namespace DCorePHP\Model;

use DateTime;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use Exception;

class TransactionDetail
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var int */
    private $type;
    /** @var AssetAmount */
    private $amount;
    /** @var AssetAmount */
    private $fee;
    /** @var ChainObject */
    private $nftDataId;
    /** @var string */
    private $description;
    /** @var DateTime */
    private $timestamp;

    public function __construct()
    {
        $this->setAmount(new AssetAmount());
        $this->setFee(new AssetAmount());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     *
     * @return TransactionDetail
     * @throws ValidationException
     */
    public function setId(string $id): TransactionDetail
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
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param ChainObject|string $from
     *
     * @return TransactionDetail
     * @throws ValidationException
     */
    public function setFrom(string $from): TransactionDetail
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param ChainObject|string $to
     *
     * @return TransactionDetail
     * @throws ValidationException
     */
    public function setTo(string $to): TransactionDetail
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return TransactionDetail
     */
    public function setType(int $type): TransactionDetail
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getAmount(): AssetAmount
    {
        return $this->amount;
    }

    /**
     * @param AssetAmount $amount
     * @return TransactionDetail
     */
    public function setAmount(AssetAmount $amount): TransactionDetail
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getFee(): AssetAmount
    {
        return $this->fee;
    }

    /**
     * @param AssetAmount $fee
     * @return TransactionDetail
     */
    public function setFee(AssetAmount $fee): TransactionDetail
    {
        $this->fee = $fee;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getNftDataId(): ChainObject
    {
        return $this->nftDataId;
    }

    /**
     * @param ChainObject|string $nftDataId
     *
     * @return TransactionDetail
     * @throws ValidationException
     */
    public function setNftDataId($nftDataId): TransactionDetail
    {
        if (is_string($nftDataId)) {
            $nftDataId = new ChainObject($nftDataId);
        }
        $this->nftDataId = $nftDataId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return TransactionDetail
     */
    public function setDescription(string $description): TransactionDetail
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime|string $timestamp
     *
     * @return TransactionDetail
     * @throws Exception
     */
    public function setTimestamp($timestamp): TransactionDetail
    {
        $this->timestamp = $timestamp instanceof DateTime ? $timestamp : new DateTime($timestamp);
        return $this;
    }
}
