<?php

namespace DCorePHP\Model\Messaging;

use DateTime;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Crypto\Address;
use Exception;

class MessageResponse
{
    /** @var ChainObject */
    private $id;
    /** @var DateTime */
    private $created;
    /** @var ChainObject */
    private $sender;
    /** @var Address */
    private $senderAddress;
    /** @var MessageReceiver[] */
    private $receiversData;
    /** @var string */
    private $text;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return MessageResponse
     * @throws ValidationException
     */
    public function setId($id): MessageResponse
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param $created
     *
     * @return MessageResponse
     * @throws Exception
     */
    public function setCreated($created): MessageResponse
    {
        $this->created = $created instanceof DateTime ? $created : new DateTime($created);

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
     * @return MessageResponse
     * @throws ValidationException
     */
    public function setSender($sender): MessageResponse
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
    public function getSenderAddress(): ?Address
    {
        return $this->senderAddress;
    }

    /**
     * @param Address $senderAddress
     * @return MessageResponse
     */
    public function setSenderAddress(?Address $senderAddress): MessageResponse
    {
        $this->senderAddress = $senderAddress;

        return $this;
    }

    /**
     * @return MessageReceiver[]
     */
    public function getReceiversData(): array
    {
        return $this->receiversData;
    }

    /**
     * @param MessageReceiver[] $receiversData
     * @return MessageResponse
     */
    public function setReceiversData(array $receiversData): MessageResponse
    {
        $this->receiversData = $receiversData;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return MessageResponse
     */
    public function setText(string $text): MessageResponse
    {
        $this->text = $text;

        return $this;
    }
}