<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;

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
            implode('', array_reverse(str_split(
                str_pad(gmp_strval(gmp_init($this->getRegion(), 10), 16), 8, '0', STR_PAD_LEFT),
                2
            ))),
            $this->getPrice()->toBytes()
        ]);
    }

}
