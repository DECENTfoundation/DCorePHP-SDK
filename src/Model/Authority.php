<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

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
     * @param AuthMap[] $keyAuths
     * @return Authority
     */
    public function setKeyAuths(array $keyAuths): Authority
    {
        $keyAuthMaps = [];
        foreach ($keyAuths as $keyAuth) {
            $keyAuthMap = $keyAuth;
            if (!$keyAuth instanceof AuthMap && is_array($keyAuth) && count($keyAuth) === 2) {
                $keyAuthMap = new AuthMap();
                $keyAuthMap
                    ->setValue($keyAuth[0])
                    ->setWeight($keyAuth[1]);
            }

            $keyAuthMaps[] = $keyAuthMap;
        }

        $this->keyAuths = $keyAuthMaps;
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
            Math::getInt32Reversed($this->getWeightThreshold()),
            '00',
            VarInt::encodeDecToHex(sizeof($this->getKeyAuths())),
            implode('', array_map(function (AuthMap $authMap) {
                return $authMap->toBytes();
            }, $this->getKeyAuths()))
        ]);
    }
}
