<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Variant;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class NftUpdateDataOperation extends BaseOperation
{
    public const OPERATION_TYPE = 45;

    /** @var ChainObject */
    private $modifier;
    /** @var ChainObject */
    private $id;
    /** @var array */
    private $data;

    /**
     * @return ChainObject
     */
    public function getModifier(): ChainObject
    {
        return $this->modifier;
    }

    /**
     * @param ChainObject $modifier
     *
     * @return NftUpdateDataOperation
     */
    public function setModifier(ChainObject $modifier): NftUpdateDataOperation
    {
        $this->modifier = $modifier;

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
     * @return NftUpdateDataOperation
     */
    public function setId(ChainObject $id): NftUpdateDataOperation
    {
        $this->id = $id;

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
     * @return NftUpdateDataOperation
     */
    public function setData(array $data): NftUpdateDataOperation
    {
        $this->data = $data;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'modifier' => $this->getModifier()->getId(),
                'nft_data_id' => $this->getId()->getId(),
                'data' => $this->getData(),
                'fee' => $this->getFee()->toArray(),
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getModifier()->toBytes(),
            $this->getId()->toBytes(),
            VarInt::encodeDecToHex(count($this->getData())),
            implode('', array_map(static function ($element) {
                return implode('', [
                    VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($element[0]))),
                    Math::byteArrayToHex(Math::stringToByteArray($element[0])),
                    Variant::toBytes($element[1])]);
            }, $this->getData())),
            '00',
        ]);
    }
}