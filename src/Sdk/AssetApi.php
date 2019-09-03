<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
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
use DCorePHP\Net\Model\Request\GetAssetData;
use DCorePHP\Net\Model\Request\GetAssets;
use DCorePHP\Net\Model\Request\GetRealSupply;
use DCorePHP\Net\Model\Request\ListAssets;
use DCorePHP\Net\Model\Request\LookupAssets;

class AssetApi extends BaseApi implements AssetApiInterface
{
    /**
     * @inheritdoc
     */
    public function get($assetRef): Asset
    {
        if (is_string($assetRef)) {
            if (ChainObject::isValid($assetRef)) {
                return $this->get(new ChainObject($assetRef));
            }
            if (Asset::isValidSymbol($assetRef)) {
                return $this->getByName($assetRef);
            }
        }

        $assets = $this->getAll([$assetRef]);
        return reset($assets);
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
    public function getAssetsData(array $assetIds): array {
        return $this->dcoreApi->requestWebsocket(new GetAssetData($assetIds)) ?: [];
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
    public function createAssetCreateOperation(ChainObject $issuer, string $symbol, int $precision, string $description, AssetOptions $options, MonitoredAssetOptions $monitoredOptions = null, $fee = null): AssetCreateOperation
    {
        $operation = new AssetCreateOperation();
        $operation
            ->setIssuer($issuer)
            ->setSymbol($symbol)
            ->setPrecision($precision)
            ->setDescription($description)
            ->setOptions($options)
            ->setMonitoredOptions($monitoredOptions)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function create(Credentials $credentials, string $symbol, int $precision, string $description, AssetOptions $options = null, $fee = null): ?TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        $options = $options ?? (new AssetOptions())->setExchangeRate(ExchangeRate::forCreateOp(1, 1));
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAssetCreateOperation($credentials->getAccount(), $symbol, $precision, $description, $options,null, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createMonitoredAsset(Credentials $credentials, string $symbol, int $precision, string $description, MonitoredAssetOptions $options = null, $fee = null): ?TransactionConfirmation
    {
        $assetOptions = (new AssetOptions())->setExchangeRate(ExchangeRate::empty())->setMaxSupply(0);
        $options = $options ?? new MonitoredAssetOptions();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAssetCreateOperation($credentials->getAccount(), $symbol, $precision, $description, $assetOptions, $options, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createAssetUpdateOperation($asset, ExchangeRate $exchangeRate = null, string $description = null, bool $exchangeable = null, string $maxSupply = null, ChainObject $newIssuer = null, $fee = null): AssetUpdateOperation
    {
        AssetOptions::validateMaxSupply($maxSupply);
        $oldAsset = $this->get($asset);

        $operation = new AssetUpdateOperation();
        $operation
            ->setIssuer($oldAsset->getIssuer())
            ->setAssetToUpdate($oldAsset->getId())
            ->setCoreExchangeRate($exchangeRate ?: $oldAsset->getOptions()->getExchangeRate())
            ->setNewDescription($description ?: $oldAsset->getDescription())
            ->setExchangeable($exchangeable ?: $oldAsset->getOptions()->isExchangeable())
            ->setMaxSupply($maxSupply ?: $oldAsset->getOptions()->getMaxSupply())
            ->setNewIssuer($newIssuer)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function update(Credentials $credentials, $asset, ExchangeRate $exchangeRate = null, string $description = null, bool $exchangeable = null, string $maxSupply = null, ChainObject $newIssuer = null, $fee = null): ?TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAssetUpdateOperation($asset, $exchangeRate, $description, $exchangeable, $maxSupply, $newIssuer, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createAssetUpdateAdvancedOperation($asset, int $precision = null, bool $fixedMaxSupply = null, $fee = null): AssetUpdateAdvancedOperation {
        $oldAsset = $this->get($asset);
        $operation = AssetUpdateAdvancedOperation::create($oldAsset);
        $operation
            ->setPrecision($precision ?: $operation->getPrecision())
            ->setFixedMaxSupply($fixedMaxSupply ?: $operation->isFixedMaxSupply())
            ->setFee($fee);

        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function updateAdvanced(Credentials $credentials, $asset, int $precision = null, bool  $fixedMaxSupply = null, $fee = null): ?TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAssetUpdateAdvancedOperation($asset, $precision, $fixedMaxSupply, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createAssetIssueOperation($assetRef, $amount, ChainObject $to = null, Memo $memo = null, $fee = null): AssetIssueOperation {
        $asset = $this->get($assetRef);
        $operation = new AssetIssueOperation();
        $operation
            ->setIssuer($asset->getIssuer())
            ->setAssetToIssue((new AssetAmount())->setAmount($amount)->setAssetId($asset->getId()))
            ->setIssueToAccount($to ?: $asset->getIssuer())
            ->setMemo($memo)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function issue(Credentials $credentials, $assetRef, $amount, ChainObject $to = null, Memo $memo = null, $fee = null): ?TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAssetIssueOperation($assetRef, $amount, $to, $memo, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createFundPoolsOperation($assetRef, $uia, $dct, $fee = null): AssetFundPoolsOperation {
        $asset = $this->get($assetRef);
        $operation = new AssetFundPoolsOperation();
        $operation
            ->setFrom($asset->getIssuer())
            ->setUia((new AssetAmount())->setAmount($uia)->setAssetId($asset->getId()))
            ->setDct((new AssetAmount())->setAmount($dct))
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function fund(Credentials $credentials, $assetRef, $uia, $dct, $fee = null): ?TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createFundPoolsOperation($assetRef, $uia, $dct, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function createClaimFeesOperation($assetRef, $uia, $dct, $fee = null): AssetClaimFeesOperation {
        $asset = $this->get($assetRef);
        $operation = new AssetClaimFeesOperation();
        $operation
            ->setIssuer($asset->getIssuer())
            ->setUia((new AssetAmount())->setAmount($uia)->setAssetId($asset->getId()))
            ->setDct((new AssetAmount())->setAmount($dct))
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function claim(Credentials $credentials, $assetRef, $uia, $dct, $fee = null): ?TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createClaimFeesOperation($assetRef, $uia, $dct, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createReserveOperation($assetRef, $amount, $fee = null): AssetReserveOperation {
        $asset = $this->get($assetRef);
        $operation = new AssetReserveOperation();
        $operation
            ->setPayer($asset->getIssuer())
            ->setAmount((new AssetAmount())->setAmount($amount)->setAssetId($asset->getId()))
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function reserve(Credentials $credentials, $assetRef, $amount, $fee = null): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createReserveOperation($assetRef, $amount, $fee)
        );
    }
}
