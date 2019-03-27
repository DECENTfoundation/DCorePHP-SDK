<?php


namespace DCorePHP\Sdk;


use DCorePHP\Model\ChainObject;

class Subscription
{
    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var int */
    private $expiration;
    /** @var bool */
    private $renewal;

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
     * @return int
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * @param int $expiration
     * @return Subscription
     */
    public function setExpiration(int $expiration): Subscription
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutomaticRenewal(): bool
    {
        return $this->renewal;
    }

    /**
     * @param bool $renewal
     * @return Subscription
     */
    public function setAutomaticRenewal(bool $renewal): Subscription
    {
        $this->renewal = $renewal;

        return $this;
    }

}