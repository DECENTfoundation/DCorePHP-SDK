<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use Exception;

class NftTransferOperation extends BaseOperation
{
    public const OPERATION_TYPE = 44;

    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var ChainObject */
    private $id;
    /** @var Memo */
    private $memo;

    /**
     * @return ChainObject
     */
    public function getFrom(): ChainObject
    {
        return $this->from;
    }

    /**
     * @param ChainObject|string $from
     *
     * @return NftTransferOperation
     * @throws ValidationException
     */
    public function setFrom($from): NftTransferOperation
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
     *
     * @return NftTransferOperation
     * @throws ValidationException
     */
    public function setTo($to): NftTransferOperation
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     *
     * @return NftTransferOperation
     * @throws ValidationException
     */
    public function setId($id): NftTransferOperation
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return Memo
     */
    public function getMemo(): ?Memo
    {
        return $this->memo;
    }

    /**
     * @param Memo $memo
     *
     * @return NftTransferOperation
     */
    public function setMemo(?Memo $memo): NftTransferOperation
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'from' => $this->getFrom()->getId(),
                'to' => $this->getTo()->getId(),
                'nft_data_id' => $this->getId()->getId(),
                'memo' => $this->getMemo() ? $this->getMemo()->toArray() : null,
                'fee' => $this->getFee()->toArray()
            ]
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getFrom()->toBytes(),
            $this->getTo()->toBytes(),
            $this->getId()->toBytes(),
            $this->getMemo() ? $this->getMemo()->toBytes() : '00',
            '00'
        ]);
    }
}