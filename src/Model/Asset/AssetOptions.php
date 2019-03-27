<?php

namespace DCorePHP\Model\Asset;

class AssetOptions
{
    /** @var string */
    private $maxSupply = '0';
    /** @var AssetOptionsExchangeRate */
    private $exchangeRate;
    /** @var bool */
    private $exchangeable = false;
    /** @var array */
    private $extensions = [];

    public function __construct()
    {
        $this->setExchangeRate(new AssetOptionsExchangeRate());
    }

    public function getMaxSupply(): string
    {
        return $this->maxSupply;
    }

    public function setMaxSupply(string $maxSupply): self
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    public function getExchangeRate(): AssetOptionsExchangeRate
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(AssetOptionsExchangeRate $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getExchangeable(): bool
    {
        return $this->exchangeable;
    }

    public function setExchangeable(bool $exchangeable): self
    {
        $this->exchangeable = $exchangeable;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }
}
