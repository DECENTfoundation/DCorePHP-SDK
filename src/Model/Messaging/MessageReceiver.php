<?php

namespace DCorePHP\Model\Messaging;

use DCorePHP\Model\ChainObject;
use DCorePHP\Crypto\Address;

class MessageReceiver
{
    /** @var ChainObject */
    private $receiver;
    /** @var Address */
    private $receiverAddress;
    /** @var string */
    private $nonce;
    /** @var string */
    private $data;
    /**
     * @return ChainObject
     */
    public function getReceiver(): ChainObject
    {
        return $this->receiver;
    }

    /**
     * @param ChainObject|string $receiver
     * @return MessageReceiver
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setReceiver($receiver): MessageReceiver
    {
        if (is_string($receiver)) {
            $receiver = new ChainObject($receiver);
        }
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return Address
     */
    public function getReceiverAddress(): ?Address
    {
        return $this->receiverAddress;
    }

    /**
     * @param Address $receiverAddress
     * @return MessageReceiver
     */
    public function setReceiverAddress(?Address $receiverAddress): ?MessageReceiver
    {
        $this->receiverAddress = $receiverAddress;

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
     * @return MessageReceiver
     */
    public function setNonce(string $nonce): MessageReceiver
    {
        $this->nonce = $nonce;

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
     * @return MessageReceiver
     */
    public function setData(string $data): MessageReceiver
    {
        $this->data = $data;

        return $this;
    }
}