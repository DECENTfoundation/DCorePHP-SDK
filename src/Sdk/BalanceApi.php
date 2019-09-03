<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\GetAccountBalances;
use DCorePHP\Net\Model\Request\GetNamedAccountBalances;
use DCorePHP\Net\Model\Request\GetVestingBalances;

class BalanceApi extends BaseApi implements BalanceApiInterface
{
    /**
     * @inheritdoc
     */
    public function get(ChainObject $accountId, ChainObject $asset): AssetAmount
    {
        $assets = $this->getAll($accountId, [$asset]);
        return reset($assets);
    }

    /**
     * @inheritdoc
     */
    public function getAll(ChainObject $accountId, array $assets = []): array
    {
        return $this->dcoreApi->requestWebsocket(new GetAccountBalances($accountId, $assets)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $name, ChainObject $asset): AssetAmount
    {
        $assets = $this->getAllByName($name, [$asset]);
        return reset($assets);
    }

    /**
     * @inheritdoc
     */
    public function getAllByName(string $name, array $assets = []): array
    {
        return $this->dcoreApi->requestWebsocket(new GetNamedAccountBalances($name, $assets)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getWithAsset(ChainObject $accountId, string $assetSymbol = 'DCT')
    {
        $assets = $this->getAllWithAsset($accountId, [$assetSymbol]);
        return reset($assets);
    }

    /**
     * @inheritdoc
     */
    public function getAllWithAsset(ChainObject $accountId, array $assetSymbols): array
    {
        /** @var Asset[] $assets */
        $assets = $this->dcoreApi->getAssetApi()->getAllByName($assetSymbols);
        $balances = $this->getAll($accountId, array_map(static function (Asset $asset) {return $asset->getId();}, $assets));
        return array_map(
            static function (Asset $asset) use($balances) {
                return [$asset, array_filter($balances,
                    static function (AssetAmount $balance) use($asset) {
                        return $balance->getAssetId()->getId() === $asset->getId()->getId();
                    })[0]
                ];
            }, $assets);
    }

    /**
     * @inheritdoc
     */
    public function getWithAssetByName(string $name, string $assetSymbol = 'DCT')
    {
        $assets = $this->getAllWithAssetByName($name, [$assetSymbol]);
        return reset($assets);
    }

    /**
     * @inheritdoc
     */
    public function getAllWithAssetByName(string $name, array $assetSymbols): array
    {
        /** @var Asset[] $assets */
        $assets = $this->dcoreApi->getAssetApi()->getAllByName($assetSymbols);
        $balances = $this->getAllByName($name, array_map(static function (Asset $asset) {return $asset->getId();}, $assets));
        return array_map(
            static function (Asset $asset) use($balances) {
                return [$asset, array_filter($balances,
                    static function (AssetAmount $balance) use($asset) {
                        return $balance->getAssetId()->getId() === $asset->getId()->getId();
                    })[0]
                ];
            }, $assets);
    }

    /**
     * @inheritdoc
     */
    public function getAllVesting(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(new GetVestingBalances($accountId)) ?: [];
    }
}