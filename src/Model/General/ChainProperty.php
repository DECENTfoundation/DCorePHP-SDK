<?php

namespace DCorePHP\Model\General;

use DCorePHP\Model\ChainObject;

class ChainProperty
{

    /** @var ChainObject */
    private $id;

    /** @var string */
    private $chainId;

    /** @var ChainParameters */
    private $parameters;

    public function __construct()
    {
        $this->parameters = new ChainParameters();
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return ChainProperty
     */
    public function setId($id): ChainProperty
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
    public function getChainId(): string
    {
        return $this->chainId;
    }

    /**
     * @param string $chainId
     * @return ChainProperty
     */
    public function setChainId(string $chainId): ChainProperty
    {
        $this->chainId = $chainId;

        return $this;
    }

    /**
     * @return ChainParameters
     */
    public function getParameters(): ChainParameters
    {
        return $this->parameters;
    }

    /**
     * @param ChainParameters $parameters
     * @return ChainProperty
     */
    public function setParameters(ChainParameters $parameters): ChainProperty
    {
        $this->parameters = $parameters;

        return $this;
    }

}