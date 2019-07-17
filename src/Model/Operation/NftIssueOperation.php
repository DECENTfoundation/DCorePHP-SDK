<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Variant;
use DCorePHP\Utils\VarInt;
use GMP;

class NftIssueOperation extends BaseOperation
{
    public const OPERATION_TYPE = 43;

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $to;
    /** @var array */
    private $data = [];
    /** @var Memo */
    private $memo = null;

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject $issuer
     *
     * @return NftIssueOperation
     */
    public function setIssuer(ChainObject $issuer): NftIssueOperation
    {
        $this->issuer = $issuer;

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
     * @param ChainObject $id
     *
     * @return NftIssueOperation
     */
    public function setId(ChainObject $id): NftIssueOperation
    {
        $this->id = $id;

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
     * @param ChainObject $to
     *
     * @return NftIssueOperation
     */
    public function setTo(ChainObject $to): NftIssueOperation
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return NftIssueOperation
     */
    public function setData(array $data): NftIssueOperation
    {
        $this->data = $data;

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
     * @return NftIssueOperation
     */
    public function setMemo(?Memo $memo): NftIssueOperation
    {
        $this->memo = $memo;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'issuer' => $this->getIssuer()->getId(),
                'nft_id' => $this->getId()->getId(),
                'to' => $this->getTo()->getId(),
                'data' => array_map(static function ($value) {
                    return $value instanceof GMP ? 'CASTTOINT-' . gmp_strval($value) : $value;
                }, $this->getData()),
                'memo' => $this->getMemo() ? $this->getMemo()->toArray() : null,
                'fee' => $this->getFee()->toArray()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getIssuer()->toBytes(),
            $this->getTo()->toBytes(),
            $this->getId()->toBytes(),
            VarInt::encodeDecToHex(sizeof($this->getData())),
            implode('', array_map(static function ($value) { return Variant::toBytes($value); }, $this->getData())),
            $this->getMemo() ? $this->getMemo()->toBytes() : '00',
            '00'
        ]);
    }
}