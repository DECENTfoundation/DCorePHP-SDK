<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountBalances;

class BalanceApi extends BaseApi implements BalanceApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(ChainObject $accountId, ChainObject $asset): AssetAmount
    {
        // TODO: Implement get() method.
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
        // TODO: Implement getByName() method.
    }

    /**
     * @inheritDoc
     */
    public function getAllByName(string $name, array $assets): array
    {
        // TODO: Implement getAllByName() method.
    }

    /**
     * @inheritDoc
     */
    public function getWithAsset(ChainObject $accountId, string $assetSymbol = 'DCT')
    {
        // TODO: Implement getWithAsset() method.
    }

    /**
     * @inheritDoc
     */
    public function getAllWithAsset(ChainObject $accountId, array $assetSymbols): array
    {
        // TODO: Implement getAllWithAsset() method.
    }

    /**
     * @inheritDoc
     */
    public function getWithAssetByName(string $name, string $assetSymbol = 'DCT')
    {
        // TODO: Implement getWithAssetByName() method.
    }

    /**
     * @inheritDoc
     */
    public function getAllWithAssetByName(string $name, array $assetSymbols): array
    {
        // TODO: Implement getAllWithAssetByName() method.
    }

    /**
     * @inheritDoc
     */
    public function getAllVesting(ChainObject $accountId): array
    {
        // TODO: Implement getAllVesting() method.
    }
}