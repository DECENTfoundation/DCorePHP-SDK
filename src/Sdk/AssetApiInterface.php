<?php


namespace DCorePHP\Sdk;


use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\MonitoredAssetOptions;
use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

interface AssetApiInterface
{
    /**
     * get Asset object
     *
     * @param ChainObject $assetId id of asset
     * @return Asset
     * @throws \DCorePHP\Exception\ObjectNotFoundException if asset wasn't found
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     */
    public function get(ChainObject $assetId): Asset;

    /**
     * get Asset objects
     *
     * @param ChainObject[] $assetIds ids of assets
     * @return Asset[]
     * @throws \DCorePHP\Exception\ObjectNotFoundException if assets wasn't found
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     */
    public function getAll(array $assetIds): array;

    /**
     * Get current supply of the core asset
     * @return RealSupply the number of shares currently in existence in account and vesting balances, escrows and pools
     */
    public function getRealSupply(): RealSupply;

    /**
     * Lists all assets registered on the blockchain
     * @param string $lowerBound
     * @param int $limit the maximum number of assets to return (max: 100)
     * @return Asset[] the list of asset objects, ordered by symbol
     */
    public function listAllRelative(string $lowerBound, int $limit = 100): array;

    /**
     * Lookup asset by symbol
     *
     * @param string $assetSymbol eg. DCT
     * @return Asset
     */
    public function getByName(string $assetSymbol): ?Asset;

    /**
     * Lookup assets by symbol
     *
     * @param array $assetSymbols eg. DCT
     * @return Asset[]
     */
    public function getAllByName(array $assetSymbols): array;

    /**
     * Converts asset into DCT, using actual price feed
     * @param int $amountInDct
     * @param ChainObject $convertToAssetId
     * @param int $rouding
     * @return AssetAmount price in DCT
     */
    public function convertFromDct(int $amountInDct, ChainObject $convertToAssetId, int $rouding = Asset::ROUNDING_UP): AssetAmount;

    /**
     * Converts asset into DCT, using actual price feed
     * @param int $amountInDifferentAsset
     * @param ChainObject $convertFromAssetId
     * @param int $rouding
     * @return AssetAmount price in DCT
     */
    public function convertToDct(int $amountInDifferentAsset, ChainObject $convertFromAssetId, int $rouding = Asset::ROUNDING_UP): AssetAmount;

    /**
     * Returns the specific data for a given monitored asset
     * @param string $assetNameOrId the symbol or id of the monitored asset in question
     * @return MonitoredAssetOptions the specific data for this monitored asset
     */
    public function getMonitoredAssetData(string $assetNameOrId): MonitoredAssetOptions;

    /**
     * Creates a new monitored asset
     * @param string $issuer the name or id of the account who will pay the fee and become the issuer of the new asset.
     * @param string $symbol the ticker symbol of the new asset
     * @param int $precision the number of digits of precision to the right of the decimal point, must be less than or equal to 12
     * @param string $description asset description. Maximal length is 1000 chars
     * @param int $feedLifetimeSec time before a price feed expires
     * @param int $minimumFeeds minimum number of unexpired feeds required to extract a median feed from
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction creating a new asset
     */
    public function createMonitoredAsset(string $issuer, string $symbol, int $precision, string $description, int $feedLifetimeSec, int $minimumFeeds, bool $broadcast = false): BaseOperation;

    /**
     * Update the parameters specific to a monitored asset
     * @param string $symbol the name or id of the asset to update, which must be a monitored asset
     * @param string $description asset description
     * @param int $feedLifetimeSec time before a price feed expires
     * @param int $minimumFeeds minimum number of unexpired feeds required to extract a median feed from
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction updating the monitored asset
     */
    public function updateMonitoredAsset(string $symbol, string $description, int $feedLifetimeSec, int $minimumFeeds, bool $broadcast = false): BaseOperation;

    /**
     * Creates a new user-issued asset
     * @param string $issuer the name or id of the account who will pay the fee and become the issuer of the new asset
     * @param string $symbol the ticker symbol of the new asset
     * @param int $precision the number of digits of precision to the right of the decimal point, must be less than or equal to 12
     * @param string $description asset description. Maximal length is 1000 chars
     * @param int $maxSupply the maximum supply of this asset which may exist at any given time
     * @param int $coreExchangeRate core_exchange_rate is a price struct which consist from base asset and quote asset
     * @param bool $isExchangeable true to allow implicit conversion when buing content of this asset to/from core asset
     * @param bool $isSupplyFixed true to deny future modifications of 'max_supply' otherwise false
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return mixed the signed transaction creating a new asset
     */
    public function createUserIssuedAsset(string $issuer, string $symbol, int $precision, string $description, int $maxSupply, int $coreExchangeRate, bool $isExchangeable, bool $isSupplyFixed, bool $broadcast = false): BaseOperation;

    /**
     * Issue new shares of an asset
     * @param string $toAccount the name or id of the account to receive the new shares
     * @param string $amount the amount to issue, in nominal units
     * @param string $symbol the ticker symbol of the asset to issue
     * @param string $memo a memo to include in the transaction, readable by the recipient
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction issuing the new shares
     */
    public function issueAsset(string $toAccount, string $amount, string $symbol, string $memo, bool $broadcast = false): BaseOperation;

    /**
     * Update the parameters specific to a user issued asset
     * @param string $symbol the name or id of the asset to update, which must be a user-issued asset
     * @param string $newIssuer
     * @param string $description
     * @param int $masSupply
     * @param int $coreExchangeRate
     * @param bool $isExchangeable
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction updating the user-issued asset
     */
    public function updateUserIssuedAsset(string $symbol, string $newIssuer, string $description, int $masSupply, int $coreExchangeRate, bool $isExchangeable, bool $broadcast = false): BaseOperation;

    /**
     * Pay into the pools for the given asset. Allows anyone to deposit core/asset into pools
     * @param string $from the name or id of the account sending the core asset
     * @param string $uiaAmount the amount of "this" asset to deposit
     * @param string $uiaSymbol the name or id of the asset whose pool you wish to fund
     * @param string $dctAmount the amount of the core asset to deposit
     * @param string $dctSymbol the name or id of the DCT asset
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction funding the asset pools
     */
    public function fundAssetPools(string $from, string $uiaAmount, string $uiaSymbol, string $dctAmount, string $dctSymbol, bool $broadcast = false): BaseOperation;

    /**
     * Burns the given user-issued asset
     * @param string $from the account containing the asset you wish to burn
     * @param string $amount the amount to burn, in nominal units
     * @param string $symbol the name or id of the asset to burn
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction burning the asset
     */
    public function reserveAsset(string $from, string $amount, string $symbol, bool $broadcast = false): BaseOperation;

    /**
     * Transfers accumulated assets from pools back to the issuer's balance
     * @param string $uiaAmount the amount of "this" asset to claim, in nominal units
     * @param string $uiaSymbol the name or id of the asset to claim
     * @param string $dctAmount the amount of DCT asset to claim, in nominal units
     * @param string $dctSymbol the name or id of the DCT asset to claim
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction claiming the fees
     */
    public function claimFees(string $uiaAmount, string $uiaSymbol, string $dctAmount, string $dctSymbol, bool $broadcast = false): BaseOperation;

    /**
     * Publishes a price feed for the named asset
     * @param string $publishingAccount the account publishing the price feed
     * @param string $symbol the name or id of the asset whose feed we're publishing
     * @param int $feed the price feed object for particular monitored asset
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation the signed transaction updating the price feed for the given asset
     */
    public function publishAssetFeed(string $publishingAccount, string $symbol, int $feed, bool $broadcast = false): BaseOperation;
}