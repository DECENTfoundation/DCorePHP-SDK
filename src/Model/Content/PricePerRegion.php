<?php

namespace DCorePHP\Model\Content;

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
}