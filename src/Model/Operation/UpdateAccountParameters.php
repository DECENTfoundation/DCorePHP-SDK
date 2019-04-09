<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Crypto\PublicKey;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;

class UpdateAccountParameters
{
    public const OPERATION_TYPE = 100; // @todo
    public const OPERATION_NAME = 'account_update';

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
    public function getMemoKey(): ?string
    {
        return $this->memoKey;
    }

    /**
     * @param string $memoKey
     * @return UpdateAccountParameters
     */
    public function setMemoKey(string $memoKey): UpdateAccountParameters
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
     * @return UpdateAccountParameters
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setVotingAccount($votingAccount): UpdateAccountParameters
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
    public function getAllowSubscription(): ?bool
    {
        return $this->allowSubscription;
    }

    /**
     * @param bool $allowSubscription
     * @return UpdateAccountParameters
     */
    public function setAllowSubscription(bool $allowSubscription): UpdateAccountParameters
    {
        $this->allowSubscription = $allowSubscription;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPricePerSubscribe(): ?AssetAmount
    {
        return $this->pricePerSubscribe;
    }

    /**
     * @param AssetAmount $pricePerSubscribe
     * @return UpdateAccountParameters
     */
    public function setPricePerSubscribe(AssetAmount $pricePerSubscribe): UpdateAccountParameters
    {
        $this->pricePerSubscribe = $pricePerSubscribe;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumMiner(): ?int
    {
        return $this->numMiner;
    }

    /**
     * @param int $numMiner
     * @return UpdateAccountParameters
     */
    public function setNumMiner(int $numMiner): UpdateAccountParameters
    {
        $this->numMiner = $numMiner;
        return $this;
    }

    /**
     * @return array
     */
    public function getVotes(): ?array
    {
        return $this->votes;
    }

    /**
     * @param array $votes
     * @return UpdateAccountParameters
     */
    public function setVotes(array $votes): UpdateAccountParameters
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return UpdateAccountParameters
     */
    public function setExtensions(array $extensions): UpdateAccountParameters
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionPeriod(): ?int
    {
        return $this->subscriptionPeriod;
    }

    /**
     * @param int $subscriptionPeriod
     * @return UpdateAccountParameters
     */
    public function setSubscriptionPeriod(int $subscriptionPeriod): UpdateAccountParameters
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
            'allow_subscription' => $this->getAllowSubscription(),
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
            $this->getVotes() ? str_pad(dechex(count($this->getVotes())), 2, '0', STR_PAD_LEFT) . implode('', array_map(function (string $vote) {
                return implode('', array_map(function (string $number) {
                        return str_pad(dechex($number), 2, '0', STR_PAD_LEFT);
                    }, explode(':', $vote))) . '0000';
            }, $this->getVotes())) : '00',
            '00',
            str_pad(dechex($this->getAllowSubscription()), 2, '0', STR_PAD_LEFT),
            $this->getPricePerSubscribe()->toBytes(),
            implode('', array_reverse(str_split(
                str_pad(dechex($this->getSubscriptionPeriod()), 8, '0', STR_PAD_LEFT),
                2
            ))),
        ]);
    }
}
