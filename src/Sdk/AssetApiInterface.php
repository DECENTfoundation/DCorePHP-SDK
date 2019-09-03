<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\Asset\MonitoredAssetOptions;
use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Operation\AssetClaimFeesOperation;
use DCorePHP\Model\Operation\AssetCreateOperation;
use DCorePHP\Model\Operation\AssetFundPoolsOperation;
use DCorePHP\Model\Operation\AssetIssueOperation;
use DCorePHP\Model\Operation\AssetReserveOperation;
use DCorePHP\Model\Operation\AssetUpdateAdvancedOperation;
use DCorePHP\Model\Operation\AssetUpdateOperation;
use DCorePHP\Model\TransactionConfirmation;
use Exception;
use WebSocket\BadOpcodeException;

interface AssetApiInterface
{
    /**
     * get Asset object
     *
     * @param ChainObject | string $assetId id of asset
     * @return Asset
     * @throws ObjectNotFoundException if asset wasn't found
     * @throws InvalidApiCallException if DCore API returned error
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function get($assetId): Asset;

    /**
     * get Asset objects
     *
     * @param ChainObject[] $assetIds ids of assets
     * @return Asset[]
     * @throws ObjectNotFoundException if assets wasn't found
     * @throws InvalidApiCallException if DCore API returned error
     * @throws BadOpcodeException
     */
    public function getAll(array $assetIds): array;

    /**
     * Get current supply of the core asset
     * @return RealSupply the number of shares currently in existence in account and vesting balances, escrows and pools
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getRealSupply(): RealSupply;

    /**
     * Lists all assets registered on the blockchain
     * @param string $lowerBound
     * @param int $limit the maximum number of assets to return (max: 100)
     * @return Asset[] the list of asset objects, ordered by symbol
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function listAllRelative(string $lowerBound, int $limit = 100): array;

    /**
     * Lookup asset by symbol
     *
     * @param string $assetSymbol eg. DCT
     * @return Asset
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getByName(string $assetSymbol): ?Asset;

    /**
     * Lookup assets by symbol
     *
     * @param array $assetSymbols eg. DCT
     * @return Asset[]
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getAllByName(array $assetSymbols): array;

    /**
     * @param ChainObject[] $assetIds
     * @return array
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getAssetsData(array $assetIds): array;

    /**
     * Converts asset into DCT, using actual price feed
     * @param int $amountInDct
     * @param ChainObject $convertToAssetId
     * @param int $rouding
     * @return AssetAmount price in DCT
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function convertFromDct(int $amountInDct, ChainObject $convertToAssetId, int $rouding = Asset::ROUNDING_UP): AssetAmount;

    /**
     * Converts asset into DCT, using actual price feed
     * @param int $amountInDifferentAsset
     * @param ChainObject $convertFromAssetId
     * @param int $rouding
     * @return AssetAmount price in DCT
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function convertToDct(int $amountInDifferentAsset, ChainObject $convertFromAssetId, int $rouding = Asset::ROUNDING_UP): AssetAmount;

    /**
     * Create asset create operation
     *
     * @param ChainObject $issuer
     * @param string $symbol, 3-16 uppercase chars
     * @param int $precision, base unit precision, decimal places used in string representation
     * @param string $description
     * @param AssetOptions $options
     * @param MonitoredAssetOptions $monitoredOptions
     * @param $fee
     * @return AssetCreateOperation
     * @throws ValidationException
     */
    public function createAssetCreateOperation(ChainObject $issuer, string $symbol, int $precision, string $description, AssetOptions $options, MonitoredAssetOptions $monitoredOptions = null, $fee = null): AssetCreateOperation;

    /**
     * Create a new Asset
     *
     * @param Credentials $credentials
     * @param string $symbol
     * @param int $precision
     * @param string $description
     * @param AssetOptions|null $options
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Credentials $credentials, string $symbol, int $precision, string $description, AssetOptions $options = null, $fee = null): ?TransactionConfirmation;

    /**
     * Creates a new monitored asset
     * @param Credentials $credentials
     * @param string $symbol the ticker symbol of the new asset
     * @param int $precision the number of digits of precision to the right of the decimal point, must be less than or equal to 12
     * @param string $description asset description. Maximal length is 1000 chars
     * @param MonitoredAssetOptions $options
     * @param $fee
     * @return TransactionConfirmation|null the signed transaction creating a new asset
     * @throws ValidationException
     * @throws Exception
     */
    public function createMonitoredAsset(Credentials $credentials, string $symbol, int $precision, string $description, MonitoredAssetOptions $options, $fee): ?TransactionConfirmation;

    /**
     * @param $asset
     * @param ExchangeRate|null $exchangeRate
     * @param string|null $description
     * @param bool|null $exchangeable
     * @param string|null $maxSupply
     * @param ChainObject|null $newIssuer
     * @param null $fee
     * @return AssetUpdateOperation
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createAssetUpdateOperation($asset, ExchangeRate $exchangeRate = null, string $description = null, bool $exchangeable = null, string $maxSupply = null, ChainObject $newIssuer = null, $fee = null): AssetUpdateOperation;

    /**
     * @param Credentials $credentials
     * @param $asset
     * @param ExchangeRate|null $exchangeRate
     * @param string|null $description
     * @param bool|null $exchangeable
     * @param string|null $maxSupply
     * @param ChainObject|null $newIssuer
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function update(Credentials $credentials, $asset, ExchangeRate $exchangeRate = null, string $description = null, bool $exchangeable = null, string $maxSupply = null, ChainObject $newIssuer = null, $fee = null): ?TransactionConfirmation;

    /**
     * @param $asset
     * @param int|null $precision
     * @param bool|null $fixedMaxSupply
     * @param null $fee
     * @return AssetUpdateAdvancedOperation
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createAssetUpdateAdvancedOperation($asset, int $precision = null, bool $fixedMaxSupply = null, $fee = null): AssetUpdateAdvancedOperation;

    /**
     * @param Credentials $credentials
     * @param $asset
     * @param int|null $precision
     * @param bool|null $fixedMaxSupply
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function updateAdvanced(Credentials $credentials, $asset, int $precision = null, bool  $fixedMaxSupply = null, $fee = null): ?TransactionConfirmation;

    /**
     * @param $assetRef
     * @param $amount
     * @param ChainObject|null $to
     * @param Memo|null $memo
     * @param null $fee
     * @return AssetIssueOperation
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createAssetIssueOperation($assetRef, $amount, ChainObject $to = null, Memo $memo = null, $fee = null): AssetIssueOperation;

    /**
     * @param Credentials $credentials
     * @param $assetRef
     * @param $amount
     * @param ChainObject|null $to
     * @param Memo|null $memo
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws Exception
     */
    public function issue(Credentials $credentials, $assetRef, $amount, ChainObject $to = null, Memo $memo = null, $fee = null): ?TransactionConfirmation;

    /**
     * @param $assetRef
     * @param $uia
     * @param $dct
     * @param null $fee
     * @return AssetFundPoolsOperation
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createFundPoolsOperation($assetRef, $uia, $dct, $fee = null): AssetFundPoolsOperation;

    /**
     * @param Credentials $credentials
     * @param $assetRef
     * @param $uia
     * @param $dct
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function fund(Credentials $credentials, $assetRef, $uia, $dct, $fee = null): ?TransactionConfirmation;

    /**
     * @param $assetRef
     * @param $uia
     * @param $dct
     * @param null $fee
     * @return AssetClaimFeesOperation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createClaimFeesOperation($assetRef, $uia, $dct, $fee = null): AssetClaimFeesOperation;

    /**
     * @param Credentials $credentials
     * @param $assetRef
     * @param $uia
     * @param $dct
     * @param null $fee
     * @return TransactionConfirmation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function claim(Credentials $credentials, $assetRef, $uia, $dct, $fee = null): ?TransactionConfirmation;

    /**
     * @param $assetRef
     * @param $amount
     * @param null $fee
     *
     * @return AssetReserveOperation
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function createReserveOperation($assetRef, $amount, $fee = null): AssetReserveOperation;

    /**
     * @param Credentials $credentials
     * @param $assetRef
     * @param $amount
     * @param null $fee
     *
     * @return TransactionConfirmation|null
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function reserve(Credentials $credentials, $assetRef, $amount, $fee = null): TransactionConfirmation;
}