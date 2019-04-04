<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountBalances;
use DCorePHP\Net\Model\Request\GetNamedAccountBalances;
use DCorePHP\Net\Model\Request\GetVestingBalances;

class BalanceApi extends BaseApi implements BalanceApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(ChainObject $accountId, ChainObject $asset): AssetAmount
    {
        $assets = $this->getAll($accountId, [$asset]);
        return reset($assets);
    }

    /**
     * @inheritDoc
     */
    public function getAll(ChainObject $accountId, array $assets = []): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetAccountBalances($accountId, $assets)) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, ChainObject $asset): AssetAmount
    {
        $assets = $this->getAllByName($name, [$asset]);
        return reset($assets);
    }

    /**
     * @inheritDoc
     */
    public function getAllByName(string $name, array $assets = []): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetNamedAccountBalances($name, $assets)) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function getWithAsset(ChainObject $accountId, string $assetSymbol = 'DCT')
    {
        $assets = $this->getAllWithAsset($accountId, [$assetSymbol]);
        return reset($assets);
    }

    /**
     * @inheritDoc
     */
    public function getAllWithAsset(ChainObject $accountId, array $assetSymbols): array
    {
        /** @var Asset[] $assets */
        $assets = $this->dcoreApi->getAssetApi()->getAllByName($assetSymbols);
        // TODO: Map assets
//        dump(array_map(function (Asset $asset) use($accountId) {return [$asset, $this->get($accountId, $asset->getId())];}, $assets));
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getWithAssetByName(string $name, string $assetSymbol = 'DCT')
    {
        $assets = $this->getAllWithAssetByName($name, [$assetSymbol]);
        return reset($assets);
    }

    /**
     * @inheritDoc
     */
    public function getAllWithAssetByName(string $name, array $assetSymbols): array
    {
        $assets = $this->dcoreApi->getAssetApi()->getAllByName($assetSymbols);
        // TODO: Map assets
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAllVesting(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetVestingBalances($accountId)) ?: [];
    }
}