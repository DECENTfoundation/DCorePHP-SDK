<?php

namespace DCorePHP\Model\Messaging;

use DCorePHP\Model\Address;
use DCorePHP\Model\ChainObject;

class MessagePayloadReceiver
{
    /** @var ChainObject */
    private $to;
    /** @var string */
    private $data;
    /** @var Address */
    private $toAddress;
    /** @var string */
    private $nonce;

    /**
     * MessagePayloadReceiver constructor.
     * @param ChainObject $to
     * @param string $data
     * @param Address $toAddress
     * @param string $nonce
     */
    public function __construct(ChainObject $to, string $data, Address $toAddress = null, string $nonce = null)
    {
        $this->to = $to;
        $this->data = $data;
        $this->toAddress = $toAddress;
        $this->nonce = $nonce;
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
     * @return MessagePayloadReceiver
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setTo($to): MessagePayloadReceiver
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return MessagePayloadReceiver
     */
    public function setData(string $data): MessagePayloadReceiver
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Address
     */
    public function getToAddress(): Address
    {
        return $this->toAddress;
    }

    /**
     * @param Address $toAddress
     * @return MessagePayloadReceiver
     */
    public function setToAddress(Address $toAddress): MessagePayloadReceiver
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     * @return MessagePayloadReceiver
     */
    public function setNonce(string $nonce): MessagePayloadReceiver
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'to' => $this->getTo()->getId(),
            'data' => $this->getData(),
//            'pub_to' => $this->getToAddress()->getPublicKey(),
//            'nonce' => $this->getNonce()
        ];
    }
}