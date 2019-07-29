<?php

namespace DCorePHP\Model;

use DCorePHP\Exception\ValidationException;

class Nft
{
    /** @var ChainObject */
    private $id;
    /** @var string */
    private $symbol;
    /** @var NftOptions */
    private $options;
    /** @var NftDataType[] */
    private $definitions;
    /** @var bool */
    private $fixedMaxSupply;
    /** @var bool */
    private $transferable;
    /** @var string */
    private $currentSupply;

    /**
     * Nft constructor.
     */
    public function __construct()
    {
        $this->options = new NftOptions();
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
     * @return Nft
     * @throws ValidationException
     */
    public function setId($id): Nft
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return Nft
     */
    public function setSymbol(string $symbol): Nft
    {
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
     * @return Nft
     */
    public function setOptions(NftOptions $options): Nft
    {
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
     * @return Nft
     */
    public function setDefinitions(array $definitions): Nft
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFixedMaxSupply(): bool
    {
        return $this->fixedMaxSupply;
    }

    /**
     * @param bool $fixedMaxSupply
     * @return Nft
     */
    public function setFixedMaxSupply(bool $fixedMaxSupply): Nft
    {
        $this->fixedMaxSupply = $fixedMaxSupply;

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
     * @return Nft
     */
    public function setTransferable(bool $transferable): Nft
    {
        $this->transferable = $transferable;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentSupply(): string
    {
        return $this->currentSupply;
    }

    /**
     * @param string $currentSupply
     * @return Nft
     */
    public function setCurrentSupply(string $currentSupply): Nft
    {
        $this->currentSupply = $currentSupply;

        return $this;
    }
}