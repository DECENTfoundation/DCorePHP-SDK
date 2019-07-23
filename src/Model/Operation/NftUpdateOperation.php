<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\DCoreConstants;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftOptions;
use InvalidArgumentException;

class NftUpdateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 42;

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $id;
    /** @var NftOptions */
    private $options;

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject|string $issuer
     * @return NftUpdateOperation
     * @throws ValidationException
     */
    public function setIssuer($issuer): NftUpdateOperation
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
     * @return NftUpdateOperation
     * @throws ValidationException
     */
    public function setId($id): NftUpdateOperation
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return NftOptions
     */
    public function getOptions(): NftOptions
    {
        return $this->options;
    }

    /**
     * @param NftOptions $options
     * @return NftUpdateOperation
     * @throws ValidationException
     */
    public function setOptions(NftOptions $options): NftUpdateOperation
    {
        if (strlen($options->getDescription()) > DCoreConstants::UIA_DESCRIPTION_MAX_CHARS) {
            throw new InvalidArgumentException('description cannot be longer then ' . DCoreConstants::UIA_DESCRIPTION_MAX_CHARS . 'chars');
        }
        $options->validateMaxSupply();

        $this->options = $options;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'current_issuer' => $this->getIssuer()->getId(),
                'nft_id' => $this->getId()->getId(),
                'options' => $this->getOptions()->toArray(),
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
            $this->getId()->toBytes(),
            $this->getOptions()->toBytes(),
            '00'
        ]);
    }
}