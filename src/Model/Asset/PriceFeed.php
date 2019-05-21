<?php

namespace DCorePHP\Model\Asset;

class PriceFeed
{
    /** @var ExchangeRate */
    private $coreExchangeRate;

    /**
     * PriceFeed constructor.
     */
    public function __construct()
    {
        $this->coreExchangeRate = ExchangeRate::empty();
    }

    /**
     * @return ExchangeRate
     */
    public function getCoreExchangeRate(): ExchangeRate
    {
        return $this->coreExchangeRate;
    }

    /**
     * @param ExchangeRate $coreExchangeRate
     * @return self
     */
    public function setCoreExchangeRate(ExchangeRate $coreExchangeRate): self
    {
        $this->coreExchangeRate = $coreExchangeRate;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'core_exchange_rate' => $this->getCoreExchangeRate()->toArray()
        ];
    }

}