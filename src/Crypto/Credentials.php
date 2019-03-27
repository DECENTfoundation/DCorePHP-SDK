<?php

namespace DCorePHP\Crypto;

use DCorePHP\Model\ChainObject;

class Credentials
{
    /** @var ChainObject */
    private $account;
    /** @var ECKeyPair */
    private $keyPair;

    /**
     * Credentials constructor.
     * @param ChainObject $account
     * @param ECKeyPair $ECKeyPair
     */
    public function __construct(ChainObject $account, ECKeyPair $ECKeyPair)
    {
        $this->account = $account;
        $this->keyPair = $ECKeyPair;
    }

    /**
     * @return ChainObject
     */
    public function getAccount(): ChainObject
    {
        return $this->account;
    }

    /**
     * @param ChainObject|string $account
     * @return Credentials
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAccount($account): Credentials
    {
        if (is_string($account)) {
            $account = new ChainObject($account);
        }

        $this->account = $account;

        return $this;
    }

    /**
     * @return ECKeyPair
     */
    public function getKeyPair(): ECKeyPair
    {
        return $this->keyPair;
    }

    /**
     * @param ECKeyPair $keyPair
     * @return Credentials
     */
    public function setKeyPair(ECKeyPair $keyPair): Credentials
    {
        $this->keyPair = $keyPair;

        return $this;
    }
}