<?php

namespace DCorePHP\Model\Asset;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;

class ExchangeRate
{
    /** @var AssetAmount */
    private $base;
    /** @var AssetAmount */
    private $quote;

    /**
     * ExchangeRate constructor.
     * @param AssetAmount $base
     * @param AssetAmount $quote
     */
    public function __construct(AssetAmount $base, AssetAmount $quote)
    {
        $this->base = $base;
        $this->quote = $quote;
    }

    /**
     * @return ExchangeRate
     */
    public static function empty(): self {
        return new self((new AssetAmount())->setAmount(0), (new AssetAmount())->setAmount(0));
    }

    /**
     * quote & base asset ids cannot be the same, for quote any id can be used since it is modified to created asset id upon creation
     * @param int $base
     * @param int $quote
     * @return ExchangeRate
     * @throws ValidationException
     */
    public static function forCreateOp(int $base, int $quote): self {
        return new self((new AssetAmount())->setAmount($base), (new AssetAmount())->setAmount($quote)->setAssetId(new ChainObject('1.3.1')));
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

    public function toArray(): array
    {
        return [
            'base' => $this->getBase()->toArray(),
            'quote' => $this->getQuote()->toArray()
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getBase()->toBytes(),
            $this->getQuote()->toBytes()
        ]);
    }
}
