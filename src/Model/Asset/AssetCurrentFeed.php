<?php


namespace DCorePHP\Model\Asset;

class AssetCurrentFeed
{
    /** @var AssetExchangeRate */
    private $coreExchangeRate;

    /**
     * @return AssetExchangeRate
     */
    public function getCoreExchangeRate(): AssetExchangeRate
    {
        return $this->coreExchangeRate;
    }

    /**
     * @param AssetExchangeRate $coreExchangeRate
     * @return self
     */
    public function setCoreExchangeRate(AssetExchangeRate $coreExchangeRate): self
    {
        $this->coreExchangeRate = $coreExchangeRate;

        return $this;
    }

}