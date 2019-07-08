<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\NftDataType;
use DCorePHP\Model\Variant;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Exception;

class NftIssueOperation extends BaseOperation
{
    public const OPERATION_TYPE = 43;

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $to;
    /** @var Variant[] */
    private $data = [];
    /** @var Memo */
    private $memo;

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject|string $issuer
     *
     * @return NftIssueOperation
     * @throws ValidationException
     */
    public function setIssuer($issuer): NftIssueOperation
    {
        if (is_string($issuer)) {
            $issuer = new ChainObject($issuer);
        }
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
     * @param ChainObject|string $id
     *
     * @return NftIssueOperation
     * @throws ValidationException
     */
    public function setId($id): NftIssueOperation
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
    public function getTo(): ChainObject
    {
        return $this->to;
    }

    /**
     * @param ChainObject|string $to
     *
     * @return NftIssueOperation
     * @throws ValidationException
     */
    public function setTo($to): NftIssueOperation
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;

        return $this;
    }

    /**
     * @return Variant[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param Variant[] $data
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

    /**
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'issuer' => $this->getIssuer()->getId(),
                'nft_id' => $this->getId()->getId(),
                'to' => $this->getTo()->getId(),
                'data' => array_map(static function (Variant $variant) {
                        return $variant->getType() === 'integer' ? 'CASTTOINT-' . $variant->getValue() : $variant->getValue();
                    }, $this->getData()),
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
            $this->getIssuer()->toBytes(),
            $this->getTo()->toBytes(),
            $this->getId()->toBytes(),
            VarInt::encodeDecToHex(sizeof($this->getData())),
            implode('', array_map(static function (Variant $element) { return $element->toBytes(); }, $this->getData())),
            $this->getMemo() ? $this->getMemo()->toBytes() : '00',
            '00'
        ]);
    }

}