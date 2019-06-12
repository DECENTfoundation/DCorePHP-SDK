<?php

namespace DCorePHP\Model\Asset;

use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;

class AssetAmount
{
    /** @var int amount in satoshi, 1 = 0.00000001 BTC */
    private $amount = 0;
    /** @var ChainObject */
    private $assetId;

    public function __construct()
    {
        $this->assetId = new ChainObject('1.3.0');
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return AssetAmount
     */
    public function setAmount(int $amount): AssetAmount
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAssetId(): ?ChainObject
    {
        return $this->assetId;
    }

    /**
     * @param ChainObject | string $assetId
     * @return AssetAmount
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAssetId($assetId): AssetAmount
    {
        if (is_string($assetId)) {
            $assetId = new ChainObject($assetId);
        }

        $this->assetId = $assetId;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'asset_id' => $this->getAssetId() ? $this->getAssetId()->getId() : null,
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getAmount() ? Math::getInt64($this->getAmount()) : '0000000000000000',
            $this->getAssetId() ? $this->getAssetId()->toBytes() : '00',
        ]);
    }
}
