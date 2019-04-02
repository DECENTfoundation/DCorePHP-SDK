<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\Asset\AssetAmount;

class Options
{
    /** @var Address */
    private $memoKey;
    /** @var string */
    private $votingAccount = '1.2.3';
    /** @var int */
    private $numMiner = 0;
    /** @var array */
    private $votes = [];
    /** @var array */
    private $extensions = [];
    /** @var bool */
    private $allowSubscription = false;
    /** @var AssetAmount */
    private $pricePerSubscribe;
    /** @var int */
    private $subscriptionPeriod = 0;

    public function __construct()
    {
        $this->setPricePerSubscribe(new AssetAmount());
//        $this->memoKey = new Address();
    }

    public function getMemoKey(): ?Address
    {
        return $this->memoKey;
    }

    public function setMemoKey($memoKey): Options
    {
        if (is_string($memoKey)) {
            $memoKey = Address::decode($memoKey);
        }
        $this->memoKey = $memoKey;

        return $this;
    }

    public function getVotingAccount(): string
    {
        return $this->votingAccount;
    }

    public function setVotingAccount(string $votingAccount): Options
    {
        $this->votingAccount = $votingAccount;

        return $this;
    }

    public function getNumMiner(): int
    {
        return $this->numMiner;
    }

    public function setNumMiner(int $numMiner): Options
    {
        $this->numMiner = $numMiner;

        return $this;
    }

    public function getVotes(): array
    {
        return $this->votes;
    }

    public function setVotes(array $votes): Options
    {
        $this->votes = $votes;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): Options
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function getAllowSubscription(): bool
    {
        return $this->allowSubscription;
    }

    public function setAllowSubscription(bool $allowSubscription): Options
    {
        $this->allowSubscription = $allowSubscription;

        return $this;
    }

    public function getPricePerSubscribe(): AssetAmount
    {
        return $this->pricePerSubscribe;
    }

    public function setPricePerSubscribe(AssetAmount $pricePerSubscribe): Options
    {
        $this->pricePerSubscribe = $pricePerSubscribe;

        return $this;
    }

    public function getSubscriptionPeriod(): int
    {
        return $this->subscriptionPeriod;
    }

    public function setSubscriptionPeriod(int $subscriptionPeriod): Options
    {
        $this->subscriptionPeriod = $subscriptionPeriod;

        return $this;
    }

    public function toBytes(): string
    {
        // TODO
    }
}
