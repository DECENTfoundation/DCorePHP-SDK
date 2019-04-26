<?php

namespace DCorePHP\Model\Subscription;

use DCorePHP\Model\ChainObject;

class Subscription
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var \DateTime */
    private $expiration;
    /** @var bool */
    private $renewal;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return Subscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): Subscription
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getFrom(): ChainObject
    {
        return $this->from;
    }

    /**
     * @param ChainObject|string $from
     * @return Subscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setFrom($from): Subscription
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }
        $this->from = $from;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getTo(): ChainObject
    {
        return $this->to;
    }

    /**
     * @param ChainObject|string $to
     * @return Subscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setTo($to): Subscription
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|string $expiration
     * @return Subscription
     * @throws \Exception
     */
    public function setExpiration($expiration): Subscription
    {
        $this->expiration = $expiration instanceof \DateTime ? $expiration : new \DateTime($expiration);

        return $this;
    }

    /**
     * @return bool
     */
    public function isRenewal(): bool
    {
        return $this->renewal;
    }

    /**
     * @param bool $renewal
     * @return Subscription
     */
    public function setRenewal(bool $renewal): Subscription
    {
        $this->renewal = $renewal;

        return $this;
    }
}