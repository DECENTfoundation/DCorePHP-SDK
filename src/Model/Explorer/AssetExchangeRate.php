<?php


namespace DCorePHP\Model\Asset;


class AssetExchangeRate
{
    /** @var Asset */
    private $base;
    /** @var Asset */
    private $quote;

    /**
     * @return Asset
     */
    public function getBase(): Asset
    {
        return $this->base;
    }

    /**
     * @param Asset $base
     * @return AssetExchangeRate
     */
    public function setBase(Asset $base): AssetExchangeRate
    {
        $this->base = $base;

        return $this;
    }

    /**
     * @return Asset
     */
    public function getQuote(): Asset
    {
        return $this->quote;
    }

    /**
     * @param Asset $quote
     * @return AssetExchangeRate
     */
    public function setQuote(Asset $quote): AssetExchangeRate
    {
        $this->quote = $quote;

        return $this;
    }

}