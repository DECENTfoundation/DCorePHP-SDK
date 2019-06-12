<?php

namespace DCorePHP\Model;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class PubKey
{

    /** @var string */
    private $pubKey = '0';

    /**
     * @return string
     */
    public function getPubKey(): string
    {
        return $this->pubKey . '.';
    }

    /**
     * @param string $pubKey
     * @return PubKey
     */
    public function setPubKey(string $pubKey): PubKey
    {
        $pubKey = trim($pubKey, '.');

        $this->pubKey = $pubKey;

        return $this;
    }

    public function toArray(): array
    {
        return ['s' => $this->getPubKey()];
    }

    public function toBytes(): string
    {
        return implode('', [
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getPubKey()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getPubKey()))
        ]);
    }

}