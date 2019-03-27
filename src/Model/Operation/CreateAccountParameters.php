<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Crypto\PublicKey;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class CreateAccountParameters extends BaseOperation
{
    public const OPERATION_TYPE = 1;
    public const OPERATION_NAME = 'account_create';

    /** @var string */
    private $memoKey;
    /** @var ChainObject */
    private $votingAccount;
    /** @var bool */
    private $allowSubscription = false;
    /** @var AssetAmount */
    private $pricePerSubscribe;
    /** @var int */
    private $numMiner = 0;
    /** @var array */
    private $votes = [];
    /** @var array */
    private $extensions = [];
    /** @var int */
    private $subscriptionPeriod = 0;

    /**
     * @return string
     */
    public function getMemoKey(): string
    {
        return $this->memoKey;
    }

    /**
     * @param string $memoKey
     * @return CreateAccountParameters
     */
    public function setMemoKey(string $memoKey): CreateAccountParameters
    {
        $this->memoKey = $memoKey;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getVotingAccount(): ?ChainObject
    {
        return $this->votingAccount;
    }

    /**
     * @param ChainObject|string $votingAccount
     * @return CreateAccountParameters
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setVotingAccount($votingAccount): CreateAccountParameters
    {
        if (is_string($votingAccount)) {
            $votingAccount = new ChainObject($votingAccount);
        }

        $this->votingAccount = $votingAccount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowSubscription(): bool
    {
        return $this->allowSubscription;
    }

    /**
     * @param bool $allowSubscription
     * @return CreateAccountParameters
     */
    public function setAllowSubscription(bool $allowSubscription): CreateAccountParameters
    {
        $this->allowSubscription = $allowSubscription;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPricePerSubscribe(): AssetAmount
    {
        return $this->pricePerSubscribe;
    }

    /**
     * @param AssetAmount $pricePerSubscribe
     * @return CreateAccountParameters
     */
    public function setPricePerSubscribe(AssetAmount $pricePerSubscribe): CreateAccountParameters
    {
        $this->pricePerSubscribe = $pricePerSubscribe;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumMiner(): int
    {
        return $this->numMiner;
    }

    /**
     * @param int $numMiner
     * @return CreateAccountParameters
     */
    public function setNumMiner(int $numMiner): CreateAccountParameters
    {
        $this->numMiner = $numMiner;
        return $this;
    }

    /**
     * @return array
     */
    public function getVotes(): array
    {
        return $this->votes;
    }

    /**
     * @param array $votes
     * @return CreateAccountParameters
     */
    public function setVotes(array $votes): CreateAccountParameters
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return CreateAccountParameters
     */
    public function setExtensions(array $extensions): CreateAccountParameters
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionPeriod(): int
    {
        return $this->subscriptionPeriod;
    }

    /**
     * @param int $subscriptionPeriod
     * @return CreateAccountParameters
     */
    public function setSubscriptionPeriod(int $subscriptionPeriod): CreateAccountParameters
    {
        $this->subscriptionPeriod = $subscriptionPeriod;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'memo_key' => $this->getMemoKey(),
            'voting_account' => $this->getVotingAccount() ? $this->getVotingAccount()->getId() : null,
            'num_miner' => $this->getNumMiner(),
            'votes' => $this->getVotes(),
            'extensions' => $this->getExtensions(),
            'allow_subscription' => $this->isAllowSubscription(),
            'price_per_subscribe' => $this->getPricePerSubscribe()->toArray(),
            'subscription_period' => $this->getSubscriptionPeriod(),
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            PublicKey::fromWif($this->getMemoKey())->toCompressedPublicKey(),
            $this->getVotingAccount()->toBytes(),
            implode('', array_reverse(str_split(
                str_pad(dechex($this->getNumMiner()), 4, '0', STR_PAD_LEFT),
                2
            ))),
            '00', // @todo votes
            '00',
            str_pad(dechex($this->isAllowSubscription()), 2, '0', STR_PAD_LEFT),
            $this->getPricePerSubscribe()->toBytes(),
            implode('', array_reverse(str_split(
                str_pad(dechex($this->getSubscriptionPeriod()), 8, '0', STR_PAD_LEFT),
                2
            ))),
        ]);
    }
}
