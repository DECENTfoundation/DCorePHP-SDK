<?php

namespace DCorePHP\Model\General;

use DCorePHP\Model\ChainObject;

class Config
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
     * @return Config
     */
    public function setGrapheneSymbol(string $grapheneSymbol): Config
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
     * @return Config
     */
    public function setGrapheneAddressPrefix(string $grapheneAddressPrefix): Config
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
     * @return Config
     */
    public function setGrapheneMinAccountNameLength(int $grapheneMinAccountNameLength): Config
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
     * @return Config
     */
    public function setGrapheneMaxAccountNameLength(int $grapheneMaxAccountNameLength): Config
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
     * @return Config
     */
    public function setGrapheneMinAssetSymbolLength(int $grapheneMinAssetSymbolLength): Config
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
     * @return Config
     */
    public function setGrapheneMaxAssetSymbolLength(int $grapheneMaxAssetSymbolLength): Config
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
     * @return Config
     */
    public function setGrapheneMaxShareSupply(string $grapheneMaxShareSupply): Config
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
     * @return Config
     */
    public function setGrapheneMaxPayRate(int $grapheneMaxPayRate): Config
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
     * @return Config
     */
    public function setGrapheneMaxSigCheckDepth(int $grapheneMaxSigCheckDepth): Config
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
     * @return Config
     */
    public function setGrapheneMinTransactionSizeLimit(int $grapheneMinTransactionSizeLimit): Config
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
     * @return Config
     */
    public function setGrapheneMinBlockInterval(int $grapheneMinBlockInterval): Config
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
     * @return Config
     */
    public function setGrapheneMaxBlockInterval(int $grapheneMaxBlockInterval): Config
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
     * @return Config
     */
    public function setGrapheneDefaultBlockInterval(int $grapheneDefaultBlockInterval): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxTransactionSize(int $grapheneDefaultMaxTransactionSize): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxBlockSize(int $grapheneDefaultMaxBlockSize): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxTimeUntilExpiration(int $grapheneDefaultMaxTimeUntilExpiration): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaintenanceInterval(int $grapheneDefaultMaintenanceInterval): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaintenanceSkipSlots(int $grapheneDefaultMaintenanceSkipSlots): Config
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
     * @return Config
     */
    public function setGrapheneMinUndoHistory(int $grapheneMinUndoHistory): Config
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
     * @return Config
     */
    public function setGrapheneMaxUndoHistory(int $grapheneMaxUndoHistory): Config
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
     * @return Config
     */
    public function setGrapheneMinBlockSizeLimit(int $grapheneMinBlockSizeLimit): Config
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
     * @return Config
     */
    public function setGrapheneMinTransactionExpirationLimit(int $grapheneMinTransactionExpirationLimit): Config
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
     * @return Config
     */
    public function setGrapheneBlockchainPrecision(int $grapheneBlockchainPrecision): Config
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
     * @return Config
     */
    public function setGrapheneBlockchainPrecisionDigits(int $grapheneBlockchainPrecisionDigits): Config
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
     * @return Config
     */
    public function setGrapheneDefaultTransferFee(int $grapheneDefaultTransferFee): Config
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
     * @return Config
     */
    public function setGrapheneMaxInstanceId(string $grapheneMaxInstanceId): Config
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
     * @return Config
     */
    public function setGraphene100Percent(int $graphene100Percent): Config
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
     * @return Config
     */
    public function setGraphene1Percent(int $graphene1Percent): Config
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
     * @return Config
     */
    public function setGrapheneMaxMarketFeePercent(int $grapheneMaxMarketFeePercent): Config
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
     * @return Config
     */
    public function setGrapheneDefaultForceSettlementDelay(int $grapheneDefaultForceSettlementDelay): Config
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
     * @return Config
     */
    public function setGrapheneDefaultForceSettlementOffset(int $grapheneDefaultForceSettlementOffset): Config
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
     * @return Config
     */
    public function setGrapheneDefaultForceSettlementMaxVolume(int $grapheneDefaultForceSettlementMaxVolume): Config
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
     * @return Config
     */
    public function setGrapheneDefaultPriceFeedLifetime(int $grapheneDefaultPriceFeedLifetime): Config
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
     * @return Config
     */
    public function setGrapheneMaxFeedProducers(int $grapheneMaxFeedProducers): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxAuthorityMembership(int $grapheneDefaultMaxAuthorityMembership): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxAssetWhitelistAuthorities(int $grapheneDefaultMaxAssetWhitelistAuthorities
    ): Config {
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
     * @return Config
     */
    public function setGrapheneDefaultMaxAssetFeedPublishers(int $grapheneDefaultMaxAssetFeedPublishers): Config
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
     * @return Config
     */
    public function setGrapheneCollateralRatioDenom(int $grapheneCollateralRatioDenom): Config
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
     * @return Config
     */
    public function setGrapheneMinCollateralRatio(int $grapheneMinCollateralRatio): Config
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
     * @return Config
     */
    public function setGrapheneMaxCollateralRatio(int $grapheneMaxCollateralRatio): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaintenanceCollateralRatio(int $grapheneDefaultMaintenanceCollateralRatio): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxShortSqueezeRatio(int $grapheneDefaultMaxShortSqueezeRatio): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMarginPeriodSec(int $grapheneDefaultMarginPeriodSec): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxMiners(int $grapheneDefaultMaxMiners): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxProposalLifetimeSec(int $grapheneDefaultMaxProposalLifetimeSec): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMinerProposalReviewPeriodSec(int $grapheneDefaultMinerProposalReviewPeriodSec
    ): Config {
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
     * @return Config
     */
    public function setGrapheneDefaultNetworkPercentOfFee(int $grapheneDefaultNetworkPercentOfFee): Config
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
     * @return Config
     */
    public function setGrapheneDefaultLifetimeReferrerPercentOfFee(int $grapheneDefaultLifetimeReferrerPercentOfFee
    ): Config {
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
     * @return Config
     */
    public function setGrapheneDefaultMaxBulkDiscountPercent(int $grapheneDefaultMaxBulkDiscountPercent): Config
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
     * @return Config
     */
    public function setGrapheneDefaultBulkDiscountThresholdMin(string $grapheneDefaultBulkDiscountThresholdMin): Config
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
     * @return Config
     */
    public function setGrapheneDefaultBulkDiscountThresholdMax(string $grapheneDefaultBulkDiscountThresholdMax): Config
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
     * @return Config
     */
    public function setGrapheneDefaultCashbackVestingPeriodSec(int $grapheneDefaultCashbackVestingPeriodSec): Config
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
     * @return Config
     */
    public function setGrapheneDefaultCashbackVestingThreshold(string $grapheneDefaultCashbackVestingThreshold): Config
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
     * @return Config
     */
    public function setGrapheneDefaultBurnPercentOfFee(int $grapheneDefaultBurnPercentOfFee): Config
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
     * @return Config
     */
    public function setGrapheneMinerPayPercentPrecision(int $grapheneMinerPayPercentPrecision): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMaxAssertOpcode(int $grapheneDefaultMaxAssertOpcode): Config
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
     * @return Config
     */
    public function setGrapheneDefaultFeeLiquidationThreshold(int $grapheneDefaultFeeLiquidationThreshold): Config
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
     * @return Config
     */
    public function setGrapheneDefaultAccountsPerFeeScale(int $grapheneDefaultAccountsPerFeeScale): Config
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
     * @return Config
     */
    public function setGrapheneDefaultAccountFeeScaleBitshifts(int $grapheneDefaultAccountFeeScaleBitshifts): Config
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
     * @return Config
     */
    public function setGrapheneMaxWorkerNameLength(int $grapheneMaxWorkerNameLength): Config
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
     * @return Config
     */
    public function setGrapheneMaxUrlLength(int $grapheneMaxUrlLength): Config
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
     * @return Config
     */
    public function setGrapheneNearScheduleCtrIv(string $grapheneNearScheduleCtrIv): Config
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
     * @return Config
     */
    public function setGrapheneFarScheduleCtrIv(string $grapheneFarScheduleCtrIv): Config
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
     * @return Config
     */
    public function setGrapheneCoreAssetCycleRate(int $grapheneCoreAssetCycleRate): Config
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
     * @return Config
     */
    public function setGrapheneCoreAssetCycleRateBits(int $grapheneCoreAssetCycleRateBits): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMinerPayPerBlock(int $grapheneDefaultMinerPayPerBlock): Config
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
     * @return Config
     */
    public function setGrapheneDefaultMinerPayVestingSeconds(int $grapheneDefaultMinerPayVestingSeconds): Config
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
     * @return Config
     */
    public function setGrapheneMaxInterestApr(int $grapheneMaxInterestApr): Config
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
     * @return Config
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneMinerAccount($grapheneMinerAccount): Config
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
     * @return Config
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneNullAccount($grapheneNullAccount): Config
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
     * @return Config
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setGrapheneTempAccount($grapheneTempAccount): Config
    {
        if (is_string($grapheneTempAccount)) {
            $grapheneTempAccount = new ChainObject($grapheneTempAccount);
        }
        $this->grapheneTempAccount = $grapheneTempAccount;

        return $this;
    }

}