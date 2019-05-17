<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\AssetOptionsExchangeRate;
use DCorePHP\Model\Asset\MonitoredAssetOptions;
use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAsset;
use DCorePHP\Net\Model\Request\GetAssets;
use DCorePHP\Net\Model\Request\GetRealSupply;
use DCorePHP\Net\Model\Request\ListAssets;
use DCorePHP\Net\Model\Request\LookupAssets;
use DCorePHP\Net\Model\Request\PriceToDct;

class AssetApi extends BaseApi implements AssetApiInterface
{
    /**
     * @inheritdoc
     */
    public function get(ChainObject $assetId): Asset
    {
        return $this->dcoreApi->requestWebsocket(new GetAsset($assetId));
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $assetIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetAssets($assetIds)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getRealSupply(): RealSupply
    {
        return $this->dcoreApi->requestWebsocket(new GetRealSupply());
    }

    /**
     * @inheritdoc
     */
    public function listAllRelative(string $lowerBound, int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListAssets($lowerBound, $limit));
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $assetSymbol): ?Asset
    {
        $assets = $this->getAllByName([$assetSymbol]);
        $asset = reset($assets);

        return $asset ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getAllByName(array $assetSymbols): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupAssets($assetSymbols)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function convertFromDct(int $amountInDct, ChainObject $convertToAssetId, int $rounding = Asset::ROUNDING_UP): AssetAmount
    {
        $asset = $this->get($convertToAssetId);

        return $asset->convertFromDct($amountInDct, $rounding);
    }

    /**
     * @inheritdoc
     */
    public function convertToDct(int $amountInDifferentAsset, ChainObject $convertFromAssetId, int $rounding = Asset::ROUNDING_UP): AssetAmount
    {
        $asset = $this->get($convertFromAssetId);

        return $asset->convertToDct($amountInDifferentAsset, $rounding);
    }

    /**
     * @inheritdoc
     */
    public function getMonitoredAssetData(string $assetNameOrId): MonitoredAssetOptions
    {
        // TODO: Implement getMonitoredAssetData() method.
    }

    /**
     * @inheritdoc
     */
    public function createMonitoredAsset(
        string $issuer,
        string $symbol,
        int $precision,
        string $description,
        int $feedLifetimeSec,
        int $minimumFeeds,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement createMonitoredAsset() method.
    }

    /**
     * @inheritdoc
     */
    public function updateMonitoredAsset(
        string $symbol,
        string $description,
        int $feedLifetimeSec,
        int $minimumFeeds,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement updateMonitoredAsset() method.
    }

    /**
     * @inheritdoc
     */
    public function createUserIssuedAsset(
        string $issuer,
        string $symbol,
        int $precision,
        string $description,
        int $maxSupply,
        int $coreExchangeRate,
        bool $isExchangeable,
        bool $isSupplyFixed,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement createUserIssuedAsset() method.
    }

    /**
     * @inheritdoc
     */
    public function issueAsset(
        string $toAccount,
        string $amount,
        string $symbol,
        string $memo,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement issue_asset() method.
    }

    /**
     * @inheritdoc
     */
    public function updateUserIssuedAsset(
        string $symbol,
        string $newIssuer,
        string $description,
        int $masSupply,
        int $coreExchangeRate,
        bool $isExchangeable,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement updateUserIssuedAsset() method.
    }

    /**
     * @inheritdoc
     */
    public function fundAssetPools(
        string $from,
        string $uiaAmount,
        string $uiaSymbol,
        string $dctAmount,
        string $dctSymbol,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement fundAssetPools() method.
    }

    /**
     * @inheritdoc
     */
    public function reserveAsset(string $from, string $amount, string $symbol, bool $broadcast = false): BaseOperation
    {
        // TODO: Implement reserveAsset() method.
    }

    /**
     * @inheritdoc
     */
    public function claimFees(
        string $uiaAmount,
        string $uiaSymbol,
        string $dctAmount,
        string $dctSymbol,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement claimFees() method.
    }

    /**
     * @inheritdoc
     */
    public function publishAssetFeed(
        string $publishingAccount,
        string $symbol,
        int $feed,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement publishAssetFeed() method.
    }
}