<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\DCoreConstants;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\NftDataType;
use DCorePHP\Model\NftOptions;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use InvalidArgumentException;

class NftCreateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 41;

    /** @var string */
    private $symbol;
    /** @var NftOptions */
    private $options;
    /** @var NftDataType[] */
    private $definitions;
    /** @var bool */
    private $transferable;

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return NftCreateOperation
     */
    public function setSymbol(string $symbol): NftCreateOperation
    {
        if (!Asset::isValidSymbol($symbol)) throw new InvalidArgumentException("invalid nft symbol: $symbol");
        $this->symbol = $symbol;

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
     * @return NftCreateOperation
     * @throws ValidationException
     */
    public function setOptions(NftOptions $options): NftCreateOperation
    {
        if (strlen($options->getDescription()) > DCoreConstants::UIA_DESCRIPTION_MAX_CHARS) {
            throw new InvalidArgumentException('description cannot be longer then ' . DCoreConstants::UIA_DESCRIPTION_MAX_CHARS . 'chars');
        }
        $options->validateMaxSupply();

        $this->options = $options;

        return $this;
    }

    /**
     * @return NftDataType[]
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param NftDataType[] $definitions
     * @return NftCreateOperation
     */
    public function setDefinitions(array $definitions): NftCreateOperation
    {
        foreach ($definitions as $definition) {
            $definition->validate();
        }
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTransferable(): bool
    {
        return $this->transferable;
    }

    /**
     * @param bool $transferable
     * @return NftCreateOperation
     */
    public function setTransferable(bool $transferable): NftCreateOperation
    {
        $this->transferable = $transferable;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'symbol' => $this->getSymbol(),
                'options' => $this->getOptions()->toArray(),
                'definitions' => array_map(static function (NftDataType $data){ return $data->toArray(); }, $this->getDefinitions()),
                'transferable' => $this->isTransferable(),
                'fee' => $this->getFee()->toArray()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getSymbol()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getSymbol())),
            $this->getOptions()->toBytes(),
            Math::getInt8(count($this->getDefinitions())), // number of definitions
            $this->getDefinitions() ? implode('', array_map(static function (NftDataType $definition) { // operation bytes
                return $definition->toBytes();
            }, $this->getDefinitions())) : '00',
            $this->isTransferable() ? '01' : '00',
            '00'
        ]);
    }
}