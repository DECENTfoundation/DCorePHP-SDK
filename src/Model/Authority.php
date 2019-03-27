<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Subscription\AuthMap;

class Authority
{
    /** @var int */
    private $weightThreshold = 1;
    /** @var array */
    private $accountAuths = [];
    /** @var AuthMap[] */
    private $keyAuths;

    /**
     * @return int
     */
    public function getWeightThreshold(): int
    {
        return $this->weightThreshold;
    }

    /**
     * @param int $weightThreshold
     * @return Authority
     */
    public function setWeightThreshold(int $weightThreshold): Authority
    {
        $this->weightThreshold = $weightThreshold;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccountAuths(): array
    {
        return $this->accountAuths;
    }

    /**
     * @param array $accountAuths
     * @return Authority
     */
    public function setAccountAuths(array $accountAuths): Authority
    {
        $this->accountAuths = $accountAuths;
        return $this;
    }

    /**
     * @return array
     */
    public function getKeyAuths(): array
    {
        return $this->keyAuths;
    }

    /**
     * @param array $keyAuths
     * @return Authority
     */
    public function setKeyAuths(array $keyAuths): Authority
    {
        $this->keyAuths = $keyAuths;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'weight_threshold' => $this->getWeightThreshold(),
            'account_auths' => $this->getAccountAuths(),
            'key_auths' => array_map(function (AuthMap $authMap) {
                return $authMap->toArray();
            }, $this->getKeyAuths()),
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            implode('', array_reverse(str_split(
                str_pad(dechex($this->getWeightThreshold()), 8, '0', STR_PAD_LEFT),
                2
            ))),
            '00',
            str_pad(dechex(count($this->getKeyAuths())), 2, '0', STR_PAD_LEFT),
            implode('', array_map(function (AuthMap $authMap) {
                return $authMap->toBytes();
            }, $this->getKeyAuths()))
        ]);
    }
}
