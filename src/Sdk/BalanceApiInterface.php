<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\VestingBalance;
use WebSocket\BadOpcodeException;

interface BalanceApiInterface
{
    /**
     * Get account balance by id.
     *
     * @param ChainObject $accountId, 1.2.*
     * @param ChainObject $asset, 1.3.*
     *
     * @return AssetAmount for asset
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function get(ChainObject $accountId, ChainObject $asset): AssetAmount;

    /**
     * Get account balance by id.
     *
     * @param ChainObject $accountId, 1.2.*
     * @param ChainObject[] $assets, 1.3.*
     *
     * @return AssetAmount[] of amounts for different assets
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAll(ChainObject $accountId, array $assets = []): array;

    /**
     * Get account balance by name.
     *
     * @param string $name
     * @param ChainObject $asset , 1.3.*
     *
     * @return AssetAmount for asset
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getByName(string $name, ChainObject $asset): AssetAmount;

    /**
     * Get account balance by name.
     *
     * @param string $name
     * @param ChainObject[] $assets, 1.3.*
     *
     * @return AssetAmount[] for asset of amounts for different assets
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllByName(string $name, array $assets = []): array;

    /**
     * Get account balance.
     *
     * @param ChainObject $accountId of the account
     * @param string $assetSymbol, eg. DCT
     *
     * TODO: Return type
     * @return mixed a pair of asset to amount
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getWithAsset(ChainObject $accountId, string $assetSymbol = 'DCT');

    /**
     * Get account balance by id with asset.
     *
     * @param ChainObject $accountId of the account
     * @param array $assetSymbols
     * TODO: Return type
     * @return mixed a list of pairs of assets to amounts
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllWithAsset(ChainObject $accountId, array $assetSymbols): array;

    /**
     * Get account balance by name.
     *
     * @param string $name
     * @param string $assetSymbol , eg. DCT
     *
     * TODO: Return type
     * @return mixed a pair of asset to amount
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getWithAssetByName(string $name, string $assetSymbol = 'DCT');

    /**
     * Get account balance by name.
     *
     * @param string $name
     * @param array $assetSymbols
     * TODO: Return type
     * @return mixed a list of pairs of assets to amounts
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllWithAssetByName(string $name, array $assetSymbols): array;

    /**
     * Get information about a vesting balance object.
     *
     * @param ChainObject $accountId id of the account
     *
     * @return VestingBalance[] a list of vesting balances with additional information
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllVesting(ChainObject $accountId): array;
}