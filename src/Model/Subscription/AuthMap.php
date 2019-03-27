<?php

namespace DCorePHP\Model\Subscription;

use DCorePHP\Crypto\PublicKey;

class AuthMap
{
    /** @var string */
    private $value;
    /** @var int */
    private $weight = 1;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AuthMap
     */
    public function setValue(string $value): AuthMap
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return AuthMap
     */
    public function setWeight(int $weight): AuthMap
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getValue(),
            $this->getWeight()
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            PublicKey::fromWif($this->getValue())->toCompressedPublicKey(),
            str_pad(dechex($this->getWeight()), 2, '0', STR_PAD_LEFT) . '00',
        ]);
    }
}