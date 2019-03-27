<?php

namespace DCorePHP\Model\Messaging;

use DCorePHP\Model\Address;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;

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
        $this->receiversData = array_map(function($data) { return new MessagePayloadReceiver($data[0], Memo::withMessage($data[1])->getMessage()); }, $messages);
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
     * @throws \DCorePHP\Exception\ValidationException
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
     * @return Address
     */
    public function getFromAddress(): Address
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
        return [
            'from' => $this->getFrom()->getId(),
            'receivers_data' => array_map(function (MessagePayloadReceiver $data){ return $data->toArray(); }, $this->getReceiversData()),
//            'pub_from' => $this->getFromAddress()->getPublicKey()
        ];
    }

    public function toJson(): string
    {
//        dump(preg_replace('/(?<=nonce":)(.+)(?=}])/', '', json_encode($this->toArray())));
        return json_encode($this->toArray());
    }
}