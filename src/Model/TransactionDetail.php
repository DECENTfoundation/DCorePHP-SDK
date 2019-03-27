<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;

class TransactionDetail
{
    /** @var string */
    private $id;
    /** @var string */
    private $from;
    /** @var string */
    private $to;
    /** @var int */
    private $type;
    /** @var AssetAmount */
    private $amount;
    /** @var AssetAmount */
    private $fee;
    /** @var string */
    private $description;
    /** @var \DateTime */
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
     * @param string $id
     * @return TransactionDetail
     */
    public function setId(string $id): TransactionDetail
    {
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
     * @param string $from
     * @return TransactionDetail
     */
    public function setFrom(string $from): TransactionDetail
    {
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
     * @param string $to
     * @return TransactionDetail
     */
    public function setTo(string $to): TransactionDetail
    {
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
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime|string $timestamp
     * @return TransactionDetail
     */
    public function setTimestamp($timestamp): TransactionDetail
    {
        $this->timestamp = $timestamp instanceof \DateTime ? $timestamp : new \DateTime($timestamp);
        return $this;
    }
}
