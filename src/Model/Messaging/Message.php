<?php

namespace DCorePHP\Model\Messaging;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Model\ChainObject;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;
use DCorePHP\Utils\Math;
use InvalidArgumentException;

class Message
{
    /** @var ChainObject */
    private $operationId;
    /** @var int */
    private $timestamp;
    /** @var ChainObject */
    private $sender;
    /** @var Address */
    private $senderAddress;
    /** @var ChainObject */
    private $receiver;
    /** @var Address */
    private $receiverAddress;
    /** @var string */
    private $message;
    /** @var string */
    private $nonce;
    /** @var bool */
    private $encrypted;

    // [Kotlin - MessageResponse.kt - line 26]
    private function decryptOrNull(ECKeyPair $keyPair, Address $address): string {
        try {
            $crypto = Crypto::getInstance();

            return $crypto->decryptWithChecksum($this->message, $keyPair->getPrivate(), PublicKey::fromPoint($address->getPublicKeyPoint()), $this->getNonce());
        } catch (\Exception $e) {
            return null;
        }
    }

    public function decrypt(Credentials $credentials): Message {
        if (!$this->isEncrypted()) return $this;

        if ($this->getSenderAddress() === null) throwException(new InvalidArgumentException('Sender address was null!'));
        if ($this->getReceiverAddress() === null) throwException(new InvalidArgumentException('Receiver address was null!'));

        $address = $credentials->getAccount()->getId() === $this->getSender()->getId() ? $this->getReceiverAddress() : $this->getSenderAddress();
        $decrypted = $this->decryptOrNull($credentials->getKeyPair(), $address);
        if ($decrypted === null) return $this;
        return $this->setMessage($decrypted)->setEncrypted(false);
    }

    public static function create(MessageResponse $response): array {
        return array_map(function (MessageReceiver $receiverData) use ($response){
            $createdMessage = (new Message())
                ->setOperationId($response->getId())
                ->setTimestamp($response->getCreated())
                ->setSender($response->getSender())
                ->setSenderAddress($response->getSenderAddress())
                ->setReceiver($receiverData->getReceiver())
                ->setReceiverAddress($receiverData->getReceiverAddress())
                ->setNonce($receiverData->getNonce());

            $message = $createdMessage->isEncrypted() ? $receiverData->getData() : Math::byteArrayToString(Math::hexToByteArray(substr($receiverData->getData(), 8)));
            return $createdMessage->setMessage($message);
        }, $response->getReceiversData());
    }

    /**
     * @return ChainObject
     */
    public function getOperationId(): ChainObject
    {
        return $this->operationId;
    }

    /**
     * @param ChainObject|string $operationId
     * @return Message
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOperationId($operationId): Message
    {
        if (is_string($operationId)) {
            $operationId = new ChainObject($operationId);
        }
        $this->operationId = $operationId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return Message
     */
    public function setTimestamp(int $timestamp): Message
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getSender(): ChainObject
    {
        return $this->sender;
    }

    /**
     * @param ChainObject|string $sender
     * @return Message
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSender($sender): Message
    {
        if (is_string($sender)) {
            $sender = new ChainObject($sender);
        }
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Address
     */
    public function getSenderAddress(): Address
    {
        return $this->senderAddress;
    }

    /**
     * @param Address $senderAddress
     * @return Message
     */
    public function setSenderAddress(?Address $senderAddress): Message
    {
        $this->senderAddress = $senderAddress;

        $this->setEncrypted($this->receiverAddress !== null && $this->senderAddress !== null);

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getReceiver(): ChainObject
    {
        return $this->receiver;
    }

    /**
     * @param ChainObject|string $receiver
     * @return Message
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setReceiver($receiver): Message
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
    public function getReceiverAddress(): Address
    {
        return $this->receiverAddress;
    }

    /**
     * @param Address $receiverAddress
     * @return Message
     */
    public function setReceiverAddress(?Address $receiverAddress): Message
    {
        $this->receiverAddress = $receiverAddress;

        $this->setEncrypted($this->receiverAddress !== null && $this->senderAddress !== null);

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function setMessage(string $message): Message
    {
        $this->message = $message;

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
     * @return Message
     */
    public function setNonce(string $nonce): Message
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }

    /**
     * @param bool $encrypted
     * @return Message
     */
    public function setEncrypted(bool $encrypted): Message
    {
        $this->encrypted = $encrypted;

        return $this;
    }
}