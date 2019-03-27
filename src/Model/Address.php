<?php

namespace DCorePHP\Model;

class Address
{
    // TODO

    /** @var string */
    private $publicKey;

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     * @return Address
     */
    public function setPublicKey(string $publicKey): Address
    {
        $this->publicKey = $publicKey;

        return $this;
    }
}