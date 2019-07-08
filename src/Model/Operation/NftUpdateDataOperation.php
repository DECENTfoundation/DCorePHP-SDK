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
                'data' => $this->getDataAsArray(),
                'fee' => $this->getFee()->toArray(),
            ],
        ];
    }

    private function getDataAsArray(): array
    {
        $result = [];
        /** @var Variant $variant */
        foreach ($this->getData() as $variant) {
            $result[] = [$variant->getName(), $variant->getValue()];
        }
        return $result;
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getModifier()->toBytes(),
            $this->getId()->toBytes(),
            VarInt::encodeDecToHex(sizeof($this->getData())),
            implode('', array_map(static function (Variant $element) {
                return implode('', [
                    VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($element->getName()))),
                    Math::byteArrayToHex(Math::stringToByteArray($element->getName())),
                    $element->toBytes()]);
                }, $this->getData())),
            '00',
        ]);
    }
}