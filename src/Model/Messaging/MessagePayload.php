<?php

namespace DCorePHP\Model\Messaging;

use DCorePHP\Crypto\Address;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;

class MessagePayload
{
    /** @var ChainObject */
    private $from;
    /** @var MessagePayloadReceiver[] */
    private $receiversData;
    /** @var Address */
    private $fromAddress;

    /**
     * MessagePayload constructor.
     * @param ChainObject $from
     * @param MessagePayloadReceiver[] $messages - <ChainObject, String>
     * @param Address|null $fromAddress
     */
    public function __construct(ChainObject $from, array $messages, Address $fromAddress = null)
    {
        $this->from = $from;
        $this->receiversData = $messages;
        $this->fromAddress = $fromAddress;
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
     * @return MessagePayload
     * @throws ValidationException
     */
    public function setFrom($from): MessagePayload
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }
        $this->from = $from;

        return $this;
    }

    /**
     * @return MessagePayloadReceiver[]
     */
    public function getReceiversData(): array
    {
        return $this->receiversData;
    }

    /**
     * @param MessagePayloadReceiver[] $receiversData
     * @return MessagePayload
     */
    public function setReceiversData(array $receiversData): MessagePayload
    {
        $this->receiversData = $receiversData;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getFromAddress(): ?Address
    {
        return $this->fromAddress;
    }

    /**
     * @param Address $fromAddress
     * @return MessagePayload
     */
    public function setFromAddress(Address $fromAddress): MessagePayload
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'from' => $this->getFrom()->getId(),
            'receivers_data' => array_map(static function (MessagePayloadReceiver $data){ return $data->toArray(); }, $this->getReceiversData()),
        ];

        $this->getFromAddress() ? $array['pub_from'] = $this->getFromAddress()->encode() : null;

        return $array;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}