<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Utils\Math;

class CustodyData
{
    /** @var integer */
    private $n;
    /** @var string */
    private $uSeed;
    /** @var string */
    private $pubKey;

    /**
     * @return int
     */
    public function getN(): int
    {
        return $this->n;
    }

    /**
     * @param int $n
     * @return CustodyData
     */
    public function setN(int $n): CustodyData
    {
        $this->n = $n;

        return $this;
    }

    /**
     * @return string
     */
    public function getUSeed(): string
    {
        return $this->uSeed;
    }

    /**
     * @param string $uSeed
     * @return CustodyData
     */
    public function setUSeed(string $uSeed): CustodyData
    {
        $this->uSeed = $uSeed;

        return $this;
    }

    /**
     * @return string
     */
    public function getPubKey(): string
    {
        return $this->pubKey;
    }

    /**
     * @param string $pubKey
     * @return CustodyData
     */
    public function setPubKey(string $pubKey): CustodyData
    {
        $this->pubKey = $pubKey;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'b' => $this->getN(),
            'u_seed' => $this->getUSeed(),
            'pubKey' => $this->getPubKey()
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            str_pad(Math::reverseBytesInt($this->getN()), 8, '0', STR_PAD_LEFT),
            str_pad('', 32, '0'),
            str_pad('', 66, '0')
        ]);
    }
}