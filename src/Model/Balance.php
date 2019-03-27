<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;

class Balance
{
    /** @var AssetAmount */
    private $asset0;
    /** @var AssetAmount */
    private $asset1;

    public function __construct()
    {
        $this->asset0 = new AssetAmount();
        $this->asset1 = new AssetAmount();
    }

    /**
     * @return AssetAmount
     */
    public function getAsset0(): AssetAmount
    {
        return $this->asset0;
    }

    /**
     * @param AssetAmount $asset0
     * @return Balance
     */
    public function setAsset0(AssetAmount $asset0): Balance
    {
        $this->asset0 = $asset0;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getAsset1(): AssetAmount
    {
        return $this->asset1;
    }

    /**
     * @param AssetAmount $asset1
     * @return Balance
     */
    public function setAsset1(AssetAmount $asset1): Balance
    {
        $this->asset1 = $asset1;

        return $this;
    }
}