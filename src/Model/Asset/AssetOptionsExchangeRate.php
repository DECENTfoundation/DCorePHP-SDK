<?php

namespace DCorePHP\Model\Asset;

class AssetOptionsExchangeRate
{
    /** @var AssetAmount */
    private $base;
    /** @var AssetAmount */
    private $quote;

    public function __construct()
    {
        $this->setBase((new AssetAmount())->setAmount(1));
        $this->setQuote((new AssetAmount())->setAmount(1));
    }

    public function getBase(): AssetAmount
    {
        return $this->base;
    }

    public function setBase(AssetAmount $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getQuote(): AssetAmount
    {
        return $this->quote;
    }

    public function setQuote(AssetAmount $quote): self
    {
        $this->quote = $quote;

        return $this;
    }
}
