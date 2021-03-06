<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Utils\Math;

class RegionalPrice
{

    /** @var string */
    private $region;

    /** @var AssetAmount */
    private $price;

    public function __construct()
    {
        $this->price = new AssetAmount();
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return RegionalPrice
     */
    public function setRegion(string $region): RegionalPrice
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPrice(): AssetAmount
    {
        return $this->price;
    }

    /**
     * @param AssetAmount $price
     * @return RegionalPrice
     */
    public function setPrice(AssetAmount $price): RegionalPrice
    {
        $this->price = $price;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'price' => $this->getPrice()->toArray(),
            'region' => $this->getRegion()
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            Math::getInt32Reversed($this->getRegion()),
            $this->getPrice()->toBytes()
        ]);
    }

}
