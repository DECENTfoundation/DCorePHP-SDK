<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\RegionalPrice;

class PricePerRegion
{
    /** @var array [Region => AssetAmount] */
    private $prices;

    /**
     * @return array
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param array $prices
     * @return PricePerRegion
     */
    public function setPrices(array $prices): PricePerRegion
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return RegionalPrice[]
     */
    public function regionalPrice(): array {
        $result = [];
        foreach ($this->getPrices() as $region => $amount) {
            $result[] = (new RegionalPrice())->setRegion($region)->setPrice($amount);
        }
        return $result;
    }
}