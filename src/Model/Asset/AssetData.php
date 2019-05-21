<?php

namespace DCorePHP\Model\Asset;

use DCorePHP\Model\ChainObject;

class AssetData
{
    /** @var ChainObject */
    private $id;
    /** @var string */
    private $currentSupply;
    /** @var string */
    private $assetPool;
    /** @var string */
    private $corePool;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return AssetData
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): AssetData
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
    public function getCurrentSupply(): string
    {
        return $this->currentSupply;
    }

    /**
     * @param string $currentSupply
     * @return AssetData
     */
    public function setCurrentSupply(string $currentSupply): AssetData
    {
        $this->currentSupply = $currentSupply;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssetPool(): string
    {
        return $this->assetPool;
    }

    /**
     * @param string $assetPool
     * @return AssetData
     */
    public function setAssetPool(string $assetPool): AssetData
    {
        $this->assetPool = $assetPool;

        return $this;
    }

    /**
     * @return string
     */
    public function getCorePool(): string
    {
        return $this->corePool;
    }

    /**
     * @param string $corePool
     * @return AssetData
     */
    public function setCorePool(string $corePool): AssetData
    {
        $this->corePool = $corePool;

        return $this;
    }
}