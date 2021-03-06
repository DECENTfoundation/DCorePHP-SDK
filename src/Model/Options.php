<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\Address;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Utils\Math;

class Options
{
    /** @var Address */
    private $memoKey;
    /** @var ChainObject */
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
        $this->pricePerSubscribe = new AssetAmount();
    }

    /**
     * @return Address|null
     */
    public function getMemoKey(): ?Address
    {
        return $this->memoKey;
    }

    /**
     * @param Address|string $memoKey
     * @return Options
     * @throws \Exception
     */
    public function setMemoKey($memoKey): Options
    {
        if (is_string($memoKey)) {
            $memoKey = Address::decode($memoKey);
        }
        $this->memoKey = $memoKey;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getVotingAccount(): ChainObject
    {
        return $this->votingAccount;
    }

    /**
     * @param $votingAccount
     * @return Options
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setVotingAccount($votingAccount): Options
    {
        if (is_string($votingAccount)) {
            $votingAccount = new ChainObject($this->votingAccount);
        }
        $this->votingAccount = $votingAccount;

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
     * @return Options
     */
    public function setNumMiner(int $numMiner): Options
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
     * @return Options
     */
    public function setVotes(array $votes): Options
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
     * @return Options
     */
    public function setExtensions(array $extensions): Options
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowSubscription(): bool
    {
        return $this->allowSubscription;
    }

    /**
     * @param bool $allowSubscription
     * @return Options
     */
    public function setAllowSubscription(bool $allowSubscription): Options
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
     * @return Options
     */
    public function setPricePerSubscribe(AssetAmount $pricePerSubscribe): Options
    {
        $this->pricePerSubscribe = $pricePerSubscribe;

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
     * @return Options
     */
    public function setSubscriptionPeriod(int $subscriptionPeriod): Options
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
            'memo_key' => $this->getMemoKey()->encode(),
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
            PublicKey::fromWif($this->getMemoKey()->encode())->toCompressedPublicKey(),
            $this->getVotingAccount()->toBytes(),
            Math::getInt16Reversed($this->getNumMiner()),
            $this->getVotes() ? Math::getInt8(count($this->getVotes())) . implode('', array_map(function (string $vote) {
                    return implode('', array_map(function (string $number) {
                            return Math::getInt8($number);
                        }, explode(':', $vote))) . '0000';
                }, $this->getVotes())) : '00',
            '00',
            str_pad(Math::gmpDecHex($this->getAllowSubscription()), 2, '0', STR_PAD_LEFT),
            $this->getPricePerSubscribe()->toBytes(),
            Math::getInt32Reversed($this->getSubscriptionPeriod())
        ]);
    }
}
