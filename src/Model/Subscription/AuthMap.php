<?php

namespace DCorePHP\Model\Subscription;

use DCorePHP\Crypto\Address;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Utils\Math;
use Exception;

class AuthMap
{
    /** @var Address */
    private $value;
    /** @var int */
    private $weight = 1;

    /**
     * @return Address
     */
    public function getValue(): Address
    {
        return $this->value;
    }

    /**
     * @param Address|string $value
     *
     * @return AuthMap
     * @throws Exception
     */
    public function setValue($value): AuthMap
    {
        if (is_string($value)) {
            $value = Address::decodeCheckNull($value);
        }
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
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            $this->getValue()->encode(),
            $this->getWeight()
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            PublicKey::fromWif($this->getValue()->encode())->toCompressedPublicKey(),
            Math::getInt8($this->getWeight()) . '00',
        ]);
    }
}
