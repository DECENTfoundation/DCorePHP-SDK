<?php

namespace DCorePHP\Model\General;

use DCorePHP\Model\ChainObject;

class Configuration
{

    /** @var string */
    private $grapheneSymbol;
    /** @var string */
    private $grapheneAddressPrefix;
    /** @var int */
    private $grapheneMinAccountNameLength;
    /** @var int */
    private $grapheneMaxAccountNameLength;
    /** @var int */
    private $grapheneMinAssetSymbolLength;
    /** @var int */
    private $grapheneMaxAssetSymbolLength;
    /** @var string */
    private $grapheneMaxShareSupply;
    /** @var int */
    private $grapheneMaxPayRate;
    /** @var int */
    private $grapheneMaxSigCheckDepth;
    /** @var int */
    private $grapheneMinTransactionSizeLimit;
    /** @var int */
    private $grapheneMinBlockInterval;
    /** @var int */
    private $grapheneMaxBlockInterval;
    /** @var int */
    private $grapheneDefaultBlockInterval;
    /** @var int */
    private $grapheneDefaultMaxTransactionSize;
    /** @var int */
    private $grapheneDefaultMaxBlockSize;
    /** @var int */
    private $grapheneDefaultMaxTimeUntilExpiration;
    /** @var int */
    private $grapheneDefaultMaintenanceInterval;
    /** @var int */
    private $grapheneDefaultMaintenanceSkipSlots;
    /** @var int */
    private $grapheneMinUndoHistory;
    /** @var int */
    private $grapheneMaxUndoHistory;
    /** @var int */
    private $grapheneMinBlockSizeLimit;
    /** @var int */
    private $grapheneMinTransactionExpirationLimit;
    /** @var int */
    private $grapheneBlockchainPrecision;
    /** @var int */
    private $grapheneBlockchainPrecisionDigits;
    /** @var int */
    private $grapheneDefaultTransferFee;
    /** @var string */
    private $grapheneMaxInstanceId;
    /** @var int */
    private $graphene100Percent;
    /** @var int */
    private $graphene1Percent;
    /** @var int */
    private $grapheneMaxMarketFeePercent;
    /** @var int */
    private $grapheneDefaultForceSettlementDelay;
    /** @var int */
    private $grapheneDefaultForceSettlementOffset;
    /** @var int */
    private $grapheneDefaultForceSettlementMaxVolume;
    /** @var int */
    private $grapheneDefaultPriceFeedLifetime;
    /** @var int */
    private $grapheneMaxFeedProducers;
    /** @var int */
    private $grapheneDefaultMaxAuthorityMembership;
    /** @var int */
    private $grapheneDefaultMaxAssetWhitelistAuthorities;
    /** @var int */
    private $grapheneDefaultMaxAssetFeedPublishers;
    /** @var int */
    private $grapheneCollateralRatioDenom;
    /** @var int */
    private $grapheneMinCollateralRatio;
    /** @var int */
    private $grapheneMaxCollateralRatio;
    /** @var int */
    private $grapheneDefaultMaintenanceCollateralRatio;
    /** @var int */
    private $grapheneDefaultMaxShortSqueezeRatio;
    /** @var int */
    private $grapheneDefaultMarginPeriodSec;
    /** @var int */
    private $grapheneDefaultMaxMiners;
    /** @var int */
    private $grapheneDefaultMaxProposalLifetimeSec;
    /** @var int */
    private $grapheneDefaultMinerProposalReviewPeriodSec;
    /** @var int */
    private $grapheneDefaultNetworkPercentOfFee;
    /** @var int */
    private $grapheneDefaultLifetimeReferrerPercentOfFee;
    /** @var int */
    private $grapheneDefaultMaxBulkDiscountPercent;
    /** @var string */
    private $grapheneDefaultBulkDiscountThresholdMin;
    /** @var string */
    private $grapheneDefaultBulkDiscountThresholdMax;
    /** @var int */
    private $grapheneDefaultCashbackVestingPeriodSec;
    /** @var string */
    private $grapheneDefaultCashbackVestingThreshold;
    /** @var int */
    private $grapheneDefaultBurnPercentOfFee;
    /** @var int */
    private $grapheneMinerPayPercentPrecision;
    /** @var int */
    private $grapheneDefaultMaxAssertOpcode;
    /** @var int */
    private $grapheneDefaultFeeLiquidationThreshold;
    /** @var int */
    private $grapheneDefaultAccountsPerFeeScale;
    /** @var int */
    private $grapheneDefaultAccountFeeScaleBitshifts;
    /** @var int */
    private $grapheneMaxWorkerNameLength;
    /** @var int */
    private $grapheneMaxUrlLength;
    /** @var string */
    private $grapheneNearScheduleCtrIv;
    /** @var string */
    private $grapheneFarScheduleCtrIv;
    /** @var int */
    private $grapheneCoreAssetCycleRate;
    /** @var int */
    private $grapheneCoreAssetCycleRateBits;
    /** @var int */
    private $grapheneDefaultMinerPayPerBlock;
    /** @var int */
    private $grapheneDefaultMinerPayVestingSeconds;
    /** @var int */
    private $grapheneMaxInterestApr;
    /** @var ChainObject */
    private $grapheneMinerAccount;
    /** @var ChainObject */
    private $grapheneNullAccount;
    /** @var ChainObject */
    private $grapheneTempAccount;

    /**
     * @return string
     */
    public function getGrapheneSymbol(): string
    {
        return $this->grapheneSymbol;
    }

    /**
     * @param string $grapheneSymbol
     * @return Configuration
     */
    public function setGrapheneSymbol(string $grapheneSymbol): Configuration
    {
        $this->grapheneSymbol = $grapheneSymbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneAddressPrefix(): string
    {
        return $this->grapheneAddressPrefix;
    }

    /**
     * @param string $grapheneAddressPrefix
     * @return Configuration
     */
    public function setGrapheneAddressPrefix(string $grapheneAddressPrefix): Configuration
    {
        $this->grapheneAddressPrefix = $grapheneAddressPrefix;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinAccountNameLength(): int
    {
        return $this->grapheneMinAccountNameLength;
    }

    /**
     * @param int $grapheneMinAccountNameLength
     * @return Configuration
     */
    public function setGrapheneMinAccountNameLength(int $grapheneMinAccountNameLength): Configuration
    {
        $this->grapheneMinAccountNameLength = $grapheneMinAccountNameLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxAccountNameLength(): int
    {
        return $this->grapheneMaxAccountNameLength;
    }

    /**
     * @param int $grapheneMaxAccountNameLength
     * @return Configuration
     */
    public function setGrapheneMaxAccountNameLength(int $grapheneMaxAccountNameLength): Configuration
    {
        $this->grapheneMaxAccountNameLength = $grapheneMaxAccountNameLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinAssetSymbolLength(): int
    {
        return $this->grapheneMinAssetSymbolLength;
    }

    /**
     * @param int $grapheneMinAssetSymbolLength
     * @return Configuration
     */
    public function setGrapheneMinAssetSymbolLength(int $grapheneMinAssetSymbolLength): Configuration
    {
        $this->grapheneMinAssetSymbolLength = $grapheneMinAssetSymbolLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxAssetSymbolLength(): int
    {
        return $this->grapheneMaxAssetSymbolLength;
    }

    /**
     * @param int $grapheneMaxAssetSymbolLength
     * @return Configuration
     */
    public function setGrapheneMaxAssetSymbolLength(int $grapheneMaxAssetSymbolLength): Configuration
    {
        $this->grapheneMaxAssetSymbolLength = $grapheneMaxAssetSymbolLength;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneMaxShareSupply(): string
    {
        return $this->grapheneMaxShareSupply;
    }

    /**
     * @param string $grapheneMaxShareSupply
     * @return Configuration
     */
    public function setGrapheneMaxShareSupply(string $grapheneMaxShareSupply): Configuration
    {
        $this->grapheneMaxShareSupply = $grapheneMaxShareSupply;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxPayRate(): int
    {
        return $this->grapheneMaxPayRate;
    }

    /**
     * @param int $grapheneMaxPayRate
     * @return Configuration
     */
    public function setGrapheneMaxPayRate(int $grapheneMaxPayRate): Configuration
    {
        $this->grapheneMaxPayRate = $grapheneMaxPayRate;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxSigCheckDepth(): int
    {
        return $this->grapheneMaxSigCheckDepth;
    }

    /**
     * @param int $grapheneMaxSigCheckDepth
     * @return Configuration
     */
    public function setGrapheneMaxSigCheckDepth(int $grapheneMaxSigCheckDepth): Configuration
    {
        $this->grapheneMaxSigCheckDepth = $grapheneMaxSigCheckDepth;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinTransactionSizeLimit(): int
    {
        return $this->grapheneMinTransactionSizeLimit;
    }

    /**
     * @param int $grapheneMinTransactionSizeLimit
     * @return Configuration
     */
    public function setGrapheneMinTransactionSizeLimit(int $grapheneMinTransactionSizeLimit): Configuration
    {
        $this->grapheneMinTransactionSizeLimit = $grapheneMinTransactionSizeLimit;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinBlockInterval(): int
    {
        return $this->grapheneMinBlockInterval;
    }

    /**
     * @param int $grapheneMinBlockInterval
     * @return Configuration
     */
    public function setGrapheneMinBlockInterval(int $grapheneMinBlockInterval): Configuration
    {
        $this->grapheneMinBlockInterval = $grapheneMinBlockInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxBlockInterval(): int
    {
        return $this->grapheneMaxBlockInterval;
    }

    /**
     * @param int $grapheneMaxBlockInterval
     * @return Configuration
     */
    public function setGrapheneMaxBlockInterval(int $grapheneMaxBlockInterval): Configuration
    {
        $this->grapheneMaxBlockInterval = $grapheneMaxBlockInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultBlockInterval(): int
    {
        return $this->grapheneDefaultBlockInterval;
    }

    /**
     * @param int $grapheneDefaultBlockInterval
     * @return Configuration
     */
    public function setGrapheneDefaultBlockInterval(int $grapheneDefaultBlockInterval): Configuration
    {
        $this->grapheneDefaultBlockInterval = $grapheneDefaultBlockInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxTransactionSize(): int
    {
        return $this->grapheneDefaultMaxTransactionSize;
    }

    /**
     * @param int $grapheneDefaultMaxTransactionSize
     * @return Configuration
     */
    public function setGrapheneDefaultMaxTransactionSize(int $grapheneDefaultMaxTransactionSize): Configuration
    {
        $this->grapheneDefaultMaxTransactionSize = $grapheneDefaultMaxTransactionSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxBlockSize(): int
    {
        return $this->grapheneDefaultMaxBlockSize;
    }

    /**
     * @param int $grapheneDefaultMaxBlockSize
     * @return Configuration
     */
    public function setGrapheneDefaultMaxBlockSize(int $grapheneDefaultMaxBlockSize): Configuration
    {
        $this->grapheneDefaultMaxBlockSize = $grapheneDefaultMaxBlockSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxTimeUntilExpiration(): int
    {
        return $this->grapheneDefaultMaxTimeUntilExpiration;
    }

    /**
     * @param int $grapheneDefaultMaxTimeUntilExpiration
     * @return Configuration
     */
    public function setGrapheneDefaultMaxTimeUntilExpiration(int $grapheneDefaultMaxTimeUntilExpiration): Configuration
    {
        $this->grapheneDefaultMaxTimeUntilExpiration = $grapheneDefaultMaxTimeUntilExpiration;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaintenanceInterval(): int
    {
        return $this->grapheneDefaultMaintenanceInterval;
    }

    /**
     * @param int $grapheneDefaultMaintenanceInterval
     * @return Configuration
     */
    public function setGrapheneDefaultMaintenanceInterval(int $grapheneDefaultMaintenanceInterval): Configuration
    {
        $this->grapheneDefaultMaintenanceInterval = $grapheneDefaultMaintenanceInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaintenanceSkipSlots(): int
    {
        return $this->grapheneDefaultMaintenanceSkipSlots;
    }

    /**
     * @param int $grapheneDefaultMaintenanceSkipSlots
     * @return Configuration
     */
    public function setGrapheneDefaultMaintenanceSkipSlots(int $grapheneDefaultMaintenanceSkipSlots): Configuration
    {
        $this->grapheneDefaultMaintenanceSkipSlots = $grapheneDefaultMaintenanceSkipSlots;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinUndoHistory(): int
    {
        return $this->grapheneMinUndoHistory;
    }

    /**
     * @param int $grapheneMinUndoHistory
     * @return Configuration
     */
    public function setGrapheneMinUndoHistory(int $grapheneMinUndoHistory): Configuration
    {
        $this->grapheneMinUndoHistory = $grapheneMinUndoHistory;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxUndoHistory(): int
    {
        return $this->grapheneMaxUndoHistory;
    }

    /**
     * @param int $grapheneMaxUndoHistory
     * @return Configuration
     */
    public function setGrapheneMaxUndoHistory(int $grapheneMaxUndoHistory): Configuration
    {
        $this->grapheneMaxUndoHistory = $grapheneMaxUndoHistory;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinBlockSizeLimit(): int
    {
        return $this->grapheneMinBlockSizeLimit;
    }

    /**
     * @param int $grapheneMinBlockSizeLimit
     * @return Configuration
     */
    public function setGrapheneMinBlockSizeLimit(int $grapheneMinBlockSizeLimit): Configuration
    {
        $this->grapheneMinBlockSizeLimit = $grapheneMinBlockSizeLimit;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinTransactionExpirationLimit(): int
    {
        return $this->grapheneMinTransactionExpirationLimit;
    }

    /**
     * @param int $grapheneMinTransactionExpirationLimit
     * @return Configuration
     */
    public function setGrapheneMinTransactionExpirationLimit(int $grapheneMinTransactionExpirationLimit): Configuration
    {
        $this->grapheneMinTransactionExpirationLimit = $grapheneMinTransactionExpirationLimit;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneBlockchainPrecision(): int
    {
        return $this->grapheneBlockchainPrecision;
    }

    /**
     * @param int $grapheneBlockchainPrecision
     * @return Configuration
     */
    public function setGrapheneBlockchainPrecision(int $grapheneBlockchainPrecision): Configuration
    {
        $this->grapheneBlockchainPrecision = $grapheneBlockchainPrecision;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneBlockchainPrecisionDigits(): int
    {
        return $this->grapheneBlockchainPrecisionDigits;
    }

    /**
     * @param int $grapheneBlockchainPrecisionDigits
     * @return Configuration
     */
    public function setGrapheneBlockchainPrecisionDigits(int $grapheneBlockchainPrecisionDigits): Configuration
    {
        $this->grapheneBlockchainPrecisionDigits = $grapheneBlockchainPrecisionDigits;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultTransferFee(): int
    {
        return $this->grapheneDefaultTransferFee;
    }

    /**
     * @param int $grapheneDefaultTransferFee
     * @return Configuration
     */
    public function setGrapheneDefaultTransferFee(int $grapheneDefaultTransferFee): Configuration
    {
        $this->grapheneDefaultTransferFee = $grapheneDefaultTransferFee;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneMaxInstanceId(): string
    {
        return $this->grapheneMaxInstanceId;
    }

    /**
     * @param string $grapheneMaxInstanceId
     * @return Configuration
     */
    public function setGrapheneMaxInstanceId(string $grapheneMaxInstanceId): Configuration
    {
        $this->grapheneMaxInstanceId = $grapheneMaxInstanceId;

        return $this;
    }

    /**
     * @return int
     */
    public function getGraphene100Percent(): int
    {
        return $this->graphene100Percent;
    }

    /**
     * @param int $graphene100Percent
     * @return Configuration
     */
    public function setGraphene100Percent(int $graphene100Percent): Configuration
    {
        $this->graphene100Percent = $graphene100Percent;

        return $this;
    }

    /**
     * @return int
     */
    public function getGraphene1Percent(): int
    {
        return $this->graphene1Percent;
    }

    /**
     * @param int $graphene1Percent
     * @return Configuration
     */
    public function setGraphene1Percent(int $graphene1Percent): Configuration
    {
        $this->graphene1Percent = $graphene1Percent;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxMarketFeePercent(): int
    {
        return $this->grapheneMaxMarketFeePercent;
    }

    /**
     * @param int $grapheneMaxMarketFeePercent
     * @return Configuration
     */
    public function setGrapheneMaxMarketFeePercent(int $grapheneMaxMarketFeePercent): Configuration
    {
        $this->grapheneMaxMarketFeePercent = $grapheneMaxMarketFeePercent;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultForceSettlementDelay(): int
    {
        return $this->grapheneDefaultForceSettlementDelay;
    }

    /**
     * @param int $grapheneDefaultForceSettlementDelay
     * @return Configuration
     */
    public function setGrapheneDefaultForceSettlementDelay(int $grapheneDefaultForceSettlementDelay): Configuration
    {
        $this->grapheneDefaultForceSettlementDelay = $grapheneDefaultForceSettlementDelay;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultForceSettlementOffset(): int
    {
        return $this->grapheneDefaultForceSettlementOffset;
    }

    /**
     * @param int $grapheneDefaultForceSettlementOffset
     * @return Configuration
     */
    public function setGrapheneDefaultForceSettlementOffset(int $grapheneDefaultForceSettlementOffset): Configuration
    {
        $this->grapheneDefaultForceSettlementOffset = $grapheneDefaultForceSettlementOffset;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultForceSettlementMaxVolume(): int
    {
        return $this->grapheneDefaultForceSettlementMaxVolume;
    }

    /**
     * @param int $grapheneDefaultForceSettlementMaxVolume
     * @return Configuration
     */
    public function setGrapheneDefaultForceSettlementMaxVolume(int $grapheneDefaultForceSettlementMaxVolume): Configuration
    {
        $this->grapheneDefaultForceSettlementMaxVolume = $grapheneDefaultForceSettlementMaxVolume;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultPriceFeedLifetime(): int
    {
        return $this->grapheneDefaultPriceFeedLifetime;
    }

    /**
     * @param int $grapheneDefaultPriceFeedLifetime
     * @return Configuration
     */
    public function setGrapheneDefaultPriceFeedLifetime(int $grapheneDefaultPriceFeedLifetime): Configuration
    {
        $this->grapheneDefaultPriceFeedLifetime = $grapheneDefaultPriceFeedLifetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxFeedProducers(): int
    {
        return $this->grapheneMaxFeedProducers;
    }

    /**
     * @param int $grapheneMaxFeedProducers
     * @return Configuration
     */
    public function setGrapheneMaxFeedProducers(int $grapheneMaxFeedProducers): Configuration
    {
        $this->grapheneMaxFeedProducers = $grapheneMaxFeedProducers;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxAuthorityMembership(): int
    {
        return $this->grapheneDefaultMaxAuthorityMembership;
    }

    /**
     * @param int $grapheneDefaultMaxAuthorityMembership
     * @return Configuration
     */
    public function setGrapheneDefaultMaxAuthorityMembership(int $grapheneDefaultMaxAuthorityMembership): Configuration
    {
        $this->grapheneDefaultMaxAuthorityMembership = $grapheneDefaultMaxAuthorityMembership;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxAssetWhitelistAuthorities(): int
    {
        return $this->grapheneDefaultMaxAssetWhitelistAuthorities;
    }

    /**
     * @param int $grapheneDefaultMaxAssetWhitelistAuthorities
     * @return Configuration
     */
    public function setGrapheneDefaultMaxAssetWhitelistAuthorities(int $grapheneDefaultMaxAssetWhitelistAuthorities
    ): Configuration {
        $this->grapheneDefaultMaxAssetWhitelistAuthorities = $grapheneDefaultMaxAssetWhitelistAuthorities;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxAssetFeedPublishers(): int
    {
        return $this->grapheneDefaultMaxAssetFeedPublishers;
    }

    /**
     * @param int $grapheneDefaultMaxAssetFeedPublishers
     * @return Configuration
     */
    public function setGrapheneDefaultMaxAssetFeedPublishers(int $grapheneDefaultMaxAssetFeedPublishers): Configuration
    {
        $this->grapheneDefaultMaxAssetFeedPublishers = $grapheneDefaultMaxAssetFeedPublishers;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneCollateralRatioDenom(): int
    {
        return $this->grapheneCollateralRatioDenom;
    }

    /**
     * @param int $grapheneCollateralRatioDenom
     * @return Configuration
     */
    public function setGrapheneCollateralRatioDenom(int $grapheneCollateralRatioDenom): Configuration
    {
        $this->grapheneCollateralRatioDenom = $grapheneCollateralRatioDenom;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinCollateralRatio(): int
    {
        return $this->grapheneMinCollateralRatio;
    }

    /**
     * @param int $grapheneMinCollateralRatio
     * @return Configuration
     */
    public function setGrapheneMinCollateralRatio(int $grapheneMinCollateralRatio): Configuration
    {
        $this->grapheneMinCollateralRatio = $grapheneMinCollateralRatio;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxCollateralRatio(): int
    {
        return $this->grapheneMaxCollateralRatio;
    }

    /**
     * @param int $grapheneMaxCollateralRatio
     * @return Configuration
     */
    public function setGrapheneMaxCollateralRatio(int $grapheneMaxCollateralRatio): Configuration
    {
        $this->grapheneMaxCollateralRatio = $grapheneMaxCollateralRatio;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaintenanceCollateralRatio(): int
    {
        return $this->grapheneDefaultMaintenanceCollateralRatio;
    }

    /**
     * @param int $grapheneDefaultMaintenanceCollateralRatio
     * @return Configuration
     */
    public function setGrapheneDefaultMaintenanceCollateralRatio(int $grapheneDefaultMaintenanceCollateralRatio): Configuration
    {
        $this->grapheneDefaultMaintenanceCollateralRatio = $grapheneDefaultMaintenanceCollateralRatio;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxShortSqueezeRatio(): int
    {
        return $this->grapheneDefaultMaxShortSqueezeRatio;
    }

    /**
     * @param int $grapheneDefaultMaxShortSqueezeRatio
     * @return Configuration
     */
    public function setGrapheneDefaultMaxShortSqueezeRatio(int $grapheneDefaultMaxShortSqueezeRatio): Configuration
    {
        $this->grapheneDefaultMaxShortSqueezeRatio = $grapheneDefaultMaxShortSqueezeRatio;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMarginPeriodSec(): int
    {
        return $this->grapheneDefaultMarginPeriodSec;
    }

    /**
     * @param int $grapheneDefaultMarginPeriodSec
     * @return Configuration
     */
    public function setGrapheneDefaultMarginPeriodSec(int $grapheneDefaultMarginPeriodSec): Configuration
    {
        $this->grapheneDefaultMarginPeriodSec = $grapheneDefaultMarginPeriodSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxMiners(): int
    {
        return $this->grapheneDefaultMaxMiners;
    }

    /**
     * @param int $grapheneDefaultMaxMiners
     * @return Configuration
     */
    public function setGrapheneDefaultMaxMiners(int $grapheneDefaultMaxMiners): Configuration
    {
        $this->grapheneDefaultMaxMiners = $grapheneDefaultMaxMiners;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxProposalLifetimeSec(): int
    {
        return $this->grapheneDefaultMaxProposalLifetimeSec;
    }

    /**
     * @param int $grapheneDefaultMaxProposalLifetimeSec
     * @return Configuration
     */
    public function setGrapheneDefaultMaxProposalLifetimeSec(int $grapheneDefaultMaxProposalLifetimeSec): Configuration
    {
        $this->grapheneDefaultMaxProposalLifetimeSec = $grapheneDefaultMaxProposalLifetimeSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMinerProposalReviewPeriodSec(): int
    {
        return $this->grapheneDefaultMinerProposalReviewPeriodSec;
    }

    /**
     * @param int $grapheneDefaultMinerProposalReviewPeriodSec
     * @return Configuration
     */
    public function setGrapheneDefaultMinerProposalReviewPeriodSec(int $grapheneDefaultMinerProposalReviewPeriodSec
    ): Configuration {
        $this->grapheneDefaultMinerProposalReviewPeriodSec = $grapheneDefaultMinerProposalReviewPeriodSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultNetworkPercentOfFee(): int
    {
        return $this->grapheneDefaultNetworkPercentOfFee;
    }

    /**
     * @param int $grapheneDefaultNetworkPercentOfFee
     * @return Configuration
     */
    public function setGrapheneDefaultNetworkPercentOfFee(int $grapheneDefaultNetworkPercentOfFee): Configuration
    {
        $this->grapheneDefaultNetworkPercentOfFee = $grapheneDefaultNetworkPercentOfFee;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultLifetimeReferrerPercentOfFee(): int
    {
        return $this->grapheneDefaultLifetimeReferrerPercentOfFee;
    }

    /**
     * @param int $grapheneDefaultLifetimeReferrerPercentOfFee
     * @return Configuration
     */
    public function setGrapheneDefaultLifetimeReferrerPercentOfFee(int $grapheneDefaultLifetimeReferrerPercentOfFee
    ): Configuration {
        $this->grapheneDefaultLifetimeReferrerPercentOfFee = $grapheneDefaultLifetimeReferrerPercentOfFee;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxBulkDiscountPercent(): int
    {
        return $this->grapheneDefaultMaxBulkDiscountPercent;
    }

    /**
     * @param int $grapheneDefaultMaxBulkDiscountPercent
     * @return Configuration
     */
    public function setGrapheneDefaultMaxBulkDiscountPercent(int $grapheneDefaultMaxBulkDiscountPercent): Configuration
    {
        $this->grapheneDefaultMaxBulkDiscountPercent = $grapheneDefaultMaxBulkDiscountPercent;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneDefaultBulkDiscountThresholdMin(): string
    {
        return $this->grapheneDefaultBulkDiscountThresholdMin;
    }

    /**
     * @param string $grapheneDefaultBulkDiscountThresholdMin
     * @return Configuration
     */
    public function setGrapheneDefaultBulkDiscountThresholdMin(string $grapheneDefaultBulkDiscountThresholdMin): Configuration
    {
        $this->grapheneDefaultBulkDiscountThresholdMin = $grapheneDefaultBulkDiscountThresholdMin;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneDefaultBulkDiscountThresholdMax(): string
    {
        return $this->grapheneDefaultBulkDiscountThresholdMax;
    }

    /**
     * @param string $grapheneDefaultBulkDiscountThresholdMax
     * @return Configuration
     */
    public function setGrapheneDefaultBulkDiscountThresholdMax(string $grapheneDefaultBulkDiscountThresholdMax): Configuration
    {
        $this->grapheneDefaultBulkDiscountThresholdMax = $grapheneDefaultBulkDiscountThresholdMax;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultCashbackVestingPeriodSec(): int
    {
        return $this->grapheneDefaultCashbackVestingPeriodSec;
    }

    /**
     * @param int $grapheneDefaultCashbackVestingPeriodSec
     * @return Configuration
     */
    public function setGrapheneDefaultCashbackVestingPeriodSec(int $grapheneDefaultCashbackVestingPeriodSec): Configuration
    {
        $this->grapheneDefaultCashbackVestingPeriodSec = $grapheneDefaultCashbackVestingPeriodSec;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneDefaultCashbackVestingThreshold(): string
    {
        return $this->grapheneDefaultCashbackVestingThreshold;
    }

    /**
     * @param string $grapheneDefaultCashbackVestingThreshold
     * @return Configuration
     */
    public function setGrapheneDefaultCashbackVestingThreshold(string $grapheneDefaultCashbackVestingThreshold): Configuration
    {
        $this->grapheneDefaultCashbackVestingThreshold = $grapheneDefaultCashbackVestingThreshold;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultBurnPercentOfFee(): int
    {
        return $this->grapheneDefaultBurnPercentOfFee;
    }

    /**
     * @param int $grapheneDefaultBurnPercentOfFee
     * @return Configuration
     */
    public function setGrapheneDefaultBurnPercentOfFee(int $grapheneDefaultBurnPercentOfFee): Configuration
    {
        $this->grapheneDefaultBurnPercentOfFee = $grapheneDefaultBurnPercentOfFee;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMinerPayPercentPrecision(): int
    {
        return $this->grapheneMinerPayPercentPrecision;
    }

    /**
     * @param int $grapheneMinerPayPercentPrecision
     * @return Configuration
     */
    public function setGrapheneMinerPayPercentPrecision(int $grapheneMinerPayPercentPrecision): Configuration
    {
        $this->grapheneMinerPayPercentPrecision = $grapheneMinerPayPercentPrecision;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMaxAssertOpcode(): int
    {
        return $this->grapheneDefaultMaxAssertOpcode;
    }

    /**
     * @param int $grapheneDefaultMaxAssertOpcode
     * @return Configuration
     */
    public function setGrapheneDefaultMaxAssertOpcode(int $grapheneDefaultMaxAssertOpcode): Configuration
    {
        $this->grapheneDefaultMaxAssertOpcode = $grapheneDefaultMaxAssertOpcode;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultFeeLiquidationThreshold(): int
    {
        return $this->grapheneDefaultFeeLiquidationThreshold;
    }

    /**
     * @param int $grapheneDefaultFeeLiquidationThreshold
     * @return Configuration
     */
    public function setGrapheneDefaultFeeLiquidationThreshold(int $grapheneDefaultFeeLiquidationThreshold): Configuration
    {
        $this->grapheneDefaultFeeLiquidationThreshold = $grapheneDefaultFeeLiquidationThreshold;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultAccountsPerFeeScale(): int
    {
        return $this->grapheneDefaultAccountsPerFeeScale;
    }

    /**
     * @param int $grapheneDefaultAccountsPerFeeScale
     * @return Configuration
     */
    public function setGrapheneDefaultAccountsPerFeeScale(int $grapheneDefaultAccountsPerFeeScale): Configuration
    {
        $this->grapheneDefaultAccountsPerFeeScale = $grapheneDefaultAccountsPerFeeScale;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultAccountFeeScaleBitshifts(): int
    {
        return $this->grapheneDefaultAccountFeeScaleBitshifts;
    }

    /**
     * @param int $grapheneDefaultAccountFeeScaleBitshifts
     * @return Configuration
     */
    public function setGrapheneDefaultAccountFeeScaleBitshifts(int $grapheneDefaultAccountFeeScaleBitshifts): Configuration
    {
        $this->grapheneDefaultAccountFeeScaleBitshifts = $grapheneDefaultAccountFeeScaleBitshifts;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxWorkerNameLength(): int
    {
        return $this->grapheneMaxWorkerNameLength;
    }

    /**
     * @param int $grapheneMaxWorkerNameLength
     * @return Configuration
     */
    public function setGrapheneMaxWorkerNameLength(int $grapheneMaxWorkerNameLength): Configuration
    {
        $this->grapheneMaxWorkerNameLength = $grapheneMaxWorkerNameLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxUrlLength(): int
    {
        return $this->grapheneMaxUrlLength;
    }

    /**
     * @param int $grapheneMaxUrlLength
     * @return Configuration
     */
    public function setGrapheneMaxUrlLength(int $grapheneMaxUrlLength): Configuration
    {
        $this->grapheneMaxUrlLength = $grapheneMaxUrlLength;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneNearScheduleCtrIv(): string
    {
        return $this->grapheneNearScheduleCtrIv;
    }

    /**
     * @param string $grapheneNearScheduleCtrIv
     * @return Configuration
     */
    public function setGrapheneNearScheduleCtrIv(string $grapheneNearScheduleCtrIv): Configuration
    {
        $this->grapheneNearScheduleCtrIv = $grapheneNearScheduleCtrIv;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrapheneFarScheduleCtrIv(): string
    {
        return $this->grapheneFarScheduleCtrIv;
    }

    /**
     * @param string $grapheneFarScheduleCtrIv
     * @return Configuration
     */
    public function setGrapheneFarScheduleCtrIv(string $grapheneFarScheduleCtrIv): Configuration
    {
        $this->grapheneFarScheduleCtrIv = $grapheneFarScheduleCtrIv;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneCoreAssetCycleRate(): int
    {
        return $this->grapheneCoreAssetCycleRate;
    }

    /**
     * @param int $grapheneCoreAssetCycleRate
     * @return Configuration
     */
    public function setGrapheneCoreAssetCycleRate(int $grapheneCoreAssetCycleRate): Configuration
    {
        $this->grapheneCoreAssetCycleRate = $grapheneCoreAssetCycleRate;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneCoreAssetCycleRateBits(): int
    {
        return $this->grapheneCoreAssetCycleRateBits;
    }

    /**
     * @param int $grapheneCoreAssetCycleRateBits
     * @return Configuration
     */
    public function setGrapheneCoreAssetCycleRateBits(int $grapheneCoreAssetCycleRateBits): Configuration
    {
        $this->grapheneCoreAssetCycleRateBits = $grapheneCoreAssetCycleRateBits;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMinerPayPerBlock(): int
    {
        return $this->grapheneDefaultMinerPayPerBlock;
    }

    /**
     * @param int $grapheneDefaultMinerPayPerBlock
     * @return Configuration
     */
    public function setGrapheneDefaultMinerPayPerBlock(int $grapheneDefaultMinerPayPerBlock): Configuration
    {
        $this->grapheneDefaultMinerPayPerBlock = $grapheneDefaultMinerPayPerBlock;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneDefaultMinerPayVestingSeconds(): int
    {
        return $this->grapheneDefaultMinerPayVestingSeconds;
    }

    /**
     * @param int $grapheneDefaultMinerPayVestingSeconds
     * @return Configuration
     */
    public function setGrapheneDefaultMinerPayVestingSeconds(int $grapheneDefaultMinerPayVestingSeconds): Configuration
    {
        $this->grapheneDefaultMinerPayVestingSeconds = $grapheneDefaultMinerPayVestingSeconds;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrapheneMaxInterestApr(): int
    {
        return $this->grapheneMaxInterestApr;
    }

    /**
     * @param int $grapheneMaxInterestApr
     * @return Configuration
     */
    public function setGrapheneMaxInterestApr(int $grapheneMaxInterestApr): Configuration
    {
        $this->grapheneMaxInterestApr = $grapheneMaxInterestApr;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getGrapheneMinerAccount(): ChainObject
    {
        return $this->grapheneMinerAccount;
    }

    /**
     * @param ChainObject|string $grapheneMinerAccount
     * @return Configuration
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneMinerAccount($grapheneMinerAccount): Configuration
    {
        if (is_string($grapheneMinerAccount)) {
            $grapheneMinerAccount = new ChainObject($grapheneMinerAccount);
        }
        $this->grapheneMinerAccount = $grapheneMinerAccount;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getGrapheneNullAccount(): ChainObject
    {
        return $this->grapheneNullAccount;
    }

    /**
     * @param ChainObject|string $grapheneNullAccount
     * @return Configuration
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneNullAccount($grapheneNullAccount): Configuration
    {
        if (is_string($grapheneNullAccount)) {
            $grapheneNullAccount = new ChainObject($grapheneNullAccount);
        }
        $this->grapheneNullAccount = $grapheneNullAccount;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getGrapheneTempAccount(): ChainObject
    {
        return $this->grapheneTempAccount;
    }

    /**
     * @param ChainObject|string $grapheneTempAccount
     * @return Configuration
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneTempAccount($grapheneTempAccount): Configuration
    {
        if (is_string($grapheneTempAccount)) {
            $grapheneTempAccount = new ChainObject($grapheneTempAccount);
        }
        $this->grapheneTempAccount = $grapheneTempAccount;

        return $this;
    }

}