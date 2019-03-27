<?php


namespace DCorePHP\Model;


class BrainKeyInfo
{
    /** @var string */
    private $brainPrivateKey;
    /** @var string */
    private $wifPrivateKey;
    /** @var string */
    private $publicKey;

    /**
     * @return mixed
     */
    public function getBrainPrivateKey()
    {
        return $this->brainPrivateKey;
    }

    /**
     * @param mixed $brainPrivateKey
     * @return BrainKeyInfo
     */
    public function setBrainPrivateKey($brainPrivateKey): self
    {
        $this->brainPrivateKey = $brainPrivateKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWifPrivateKey()
    {
        return $this->wifPrivateKey;
    }

    /**
     * @param mixed $wifPrivateKey
     * @return BrainKeyInfo
     */
    public function setWifPrivateKey($wifPrivateKey): self
    {
        $this->wifPrivateKey = $wifPrivateKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param mixed $publicKey
     * @return BrainKeyInfo
     */
    public function setPublicKey($publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

}