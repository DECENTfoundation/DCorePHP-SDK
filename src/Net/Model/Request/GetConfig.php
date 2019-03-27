<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\General\Config;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetConfig extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'database',
            'get_config'
        );
    }

    public static function responseToModel(BaseResponse $response): Config
    {
        $rawConfig = $response->getResult();
        $config = new Config();
        foreach (
            [
                '[GRAPHENE_SYMBOL]' => 'grapheneSymbol',
                '[GRAPHENE_ADDRESS_PREFIX]' => 'grapheneAddressPrefix',
                '[GRAPHENE_MIN_ACCOUNT_NAME_LENGTH]' => 'grapheneMinAccountNameLength',
                '[GRAPHENE_MAX_ACCOUNT_NAME_LENGTH]' => 'grapheneMaxAccountNameLength',
                '[GRAPHENE_MIN_ASSET_SYMBOL_LENGTH]' => 'grapheneMinAssetSymbolLength',
                '[GRAPHENE_MAX_ASSET_SYMBOL_LENGTH]' => 'grapheneMaxAssetSymbolLength',
                '[GRAPHENE_MAX_SHARE_SUPPLY]' => 'grapheneMaxShareSupply',
                '[GRAPHENE_MAX_PAY_RATE]' => 'grapheneMaxPayRate',
                '[GRAPHENE_MAX_SIG_CHECK_DEPTH]' => 'grapheneMaxSigCheckDepth',
                '[GRAPHENE_MIN_TRANSACTION_SIZE_LIMIT]' => 'grapheneMinTransactionSizeLimit',
                '[GRAPHENE_MIN_BLOCK_INTERVAL]' => 'grapheneMinBlockInterval',
                '[GRAPHENE_MAX_BLOCK_INTERVAL]' => 'grapheneMaxBlockInterval',
                '[GRAPHENE_DEFAULT_BLOCK_INTERVAL]' => 'grapheneDefaultBlockInterval',
                '[GRAPHENE_DEFAULT_MAX_TRANSACTION_SIZE]' => 'grapheneDefaultMaxTransactionSize',
                '[GRAPHENE_DEFAULT_MAX_BLOCK_SIZE]' => 'grapheneDefaultMaxBlockSize',
                '[GRAPHENE_DEFAULT_MAX_TIME_UNTIL_EXPIRATION]' => 'grapheneDefaultMaxTimeUntilExpiration',
                '[GRAPHENE_DEFAULT_MAINTENANCE_INTERVAL]' => 'grapheneDefaultMaintenanceInterval',
                '[GRAPHENE_DEFAULT_MAINTENANCE_SKIP_SLOTS]' => 'grapheneDefaultMaintenanceSkipSlots',
                '[GRAPHENE_MIN_UNDO_HISTORY]' => 'grapheneMinUndoHistory',
                '[GRAPHENE_MAX_UNDO_HISTORY]' => 'grapheneMaxUndoHistory',
                '[GRAPHENE_MIN_BLOCK_SIZE_LIMIT]' => 'grapheneMinBlockSizeLimit',
                '[GRAPHENE_MIN_TRANSACTION_EXPIRATION_LIMIT]' => 'grapheneMinTransactionExpirationLimit',
                '[GRAPHENE_BLOCKCHAIN_PRECISION]' => 'grapheneBlockchainPrecision',
                '[GRAPHENE_BLOCKCHAIN_PRECISION_DIGITS]' => 'grapheneBlockchainPrecisionDigits',
                '[GRAPHENE_DEFAULT_TRANSFER_FEE]' => 'grapheneDefaultTransferFee',
                '[GRAPHENE_MAX_INSTANCE_ID]' => 'grapheneMaxInstanceId',
                '[GRAPHENE_100_PERCENT]' => 'graphene100Percent',
                '[GRAPHENE_1_PERCENT]' => 'graphene1Percent',
                '[GRAPHENE_MAX_MARKET_FEE_PERCENT]' => 'grapheneMaxMarketFeePercent',
                '[GRAPHENE_DEFAULT_FORCE_SETTLEMENT_DELAY]' => 'grapheneDefaultForceSettlementDelay',
                '[GRAPHENE_DEFAULT_FORCE_SETTLEMENT_OFFSET]' => 'grapheneDefaultForceSettlementOffset',
                '[GRAPHENE_DEFAULT_FORCE_SETTLEMENT_MAX_VOLUME]' => 'grapheneDefaultForceSettlementMaxVolume',
                '[GRAPHENE_DEFAULT_PRICE_FEED_LIFETIME]' => 'grapheneDefaultPriceFeedLifetime',
                '[GRAPHENE_MAX_FEED_PRODUCERS]' => 'grapheneMaxFeedProducers',
                '[GRAPHENE_DEFAULT_MAX_AUTHORITY_MEMBERSHIP]' => 'grapheneDefaultMaxAuthorityMembership',
                '[GRAPHENE_DEFAULT_MAX_ASSET_WHITELIST_AUTHORITIES]' => 'grapheneDefaultMaxAssetWhitelistAuthorities',
                '[GRAPHENE_DEFAULT_MAX_ASSET_FEED_PUBLISHERS]' => 'grapheneDefaultMaxAssetFeedPublishers',
                '[GRAPHENE_COLLATERAL_RATIO_DENOM]' => 'grapheneCollateralRatioDenom',
                '[GRAPHENE_MIN_COLLATERAL_RATIO]' => 'grapheneMinCollateralRatio',
                '[GRAPHENE_MAX_COLLATERAL_RATIO]' => 'grapheneMaxCollateralRatio',
                '[GRAPHENE_DEFAULT_MAINTENANCE_COLLATERAL_RATIO]' => 'grapheneDefaultMaintenanceCollateralRatio',
                '[GRAPHENE_DEFAULT_MAX_SHORT_SQUEEZE_RATIO]' => 'grapheneDefaultMaxShortSqueezeRatio',
                '[GRAPHENE_DEFAULT_MARGIN_PERIOD_SEC]' => 'grapheneDefaultMarginPeriodSec',
                '[GRAPHENE_DEFAULT_MAX_MINERS]' => 'grapheneDefaultMaxMiners',
                '[GRAPHENE_DEFAULT_MAX_PROPOSAL_LIFETIME_SEC]' => 'grapheneDefaultMaxProposalLifetimeSec',
                '[GRAPHENE_DEFAULT_MINER_PROPOSAL_REVIEW_PERIOD_SEC]' => 'grapheneDefaultMinerProposalReviewPeriodSec',
                '[GRAPHENE_DEFAULT_NETWORK_PERCENT_OF_FEE]' => 'grapheneDefaultNetworkPercentOfFee',
                '[GRAPHENE_DEFAULT_LIFETIME_REFERRER_PERCENT_OF_FEE]' => 'grapheneDefaultLifetimeReferrerPercentOfFee',
                '[GRAPHENE_DEFAULT_MAX_BULK_DISCOUNT_PERCENT]' => 'grapheneDefaultMaxBulkDiscountPercent',
                '[GRAPHENE_DEFAULT_BULK_DISCOUNT_THRESHOLD_MIN]' => 'grapheneDefaultBulkDiscountThresholdMin',
                '[GRAPHENE_DEFAULT_BULK_DISCOUNT_THRESHOLD_MAX]' => 'grapheneDefaultBulkDiscountThresholdMax',
                '[GRAPHENE_DEFAULT_CASHBACK_VESTING_PERIOD_SEC]' => 'grapheneDefaultCashbackVestingPeriodSec',
                '[GRAPHENE_DEFAULT_CASHBACK_VESTING_THRESHOLD]' => 'grapheneDefaultCashbackVestingThreshold',
                '[GRAPHENE_DEFAULT_BURN_PERCENT_OF_FEE]' => 'grapheneDefaultBurnPercentOfFee',
                '[GRAPHENE_MINER_PAY_PERCENT_PRECISION]' => 'grapheneMinerPayPercentPrecision',
                '[GRAPHENE_DEFAULT_MAX_ASSERT_OPCODE]' => 'grapheneDefaultMaxAssertOpcode',
                '[GRAPHENE_DEFAULT_FEE_LIQUIDATION_THRESHOLD]' => 'grapheneDefaultFeeLiquidationThreshold',
                '[GRAPHENE_DEFAULT_ACCOUNTS_PER_FEE_SCALE]' => 'grapheneDefaultAccountsPerFeeScale',
                '[GRAPHENE_DEFAULT_ACCOUNT_FEE_SCALE_BITSHIFTS]' => 'grapheneDefaultAccountFeeScaleBitshifts',
                '[GRAPHENE_MAX_WORKER_NAME_LENGTH]' => 'grapheneMaxWorkerNameLength',
                '[GRAPHENE_MAX_URL_LENGTH]' => 'grapheneMaxUrlLength',
                '[GRAPHENE_NEAR_SCHEDULE_CTR_IV]' => 'grapheneNearScheduleCtrIv',
                '[GRAPHENE_FAR_SCHEDULE_CTR_IV]' => 'grapheneFarScheduleCtrIv',
                '[GRAPHENE_CORE_ASSET_CYCLE_RATE]' => 'grapheneCoreAssetCycleRate',
                '[GRAPHENE_CORE_ASSET_CYCLE_RATE_BITS]' => 'grapheneCoreAssetCycleRateBits',
                '[GRAPHENE_DEFAULT_MINER_PAY_PER_BLOCK]' => 'grapheneDefaultMinerPayPerBlock',
                '[GRAPHENE_DEFAULT_MINER_PAY_VESTING_SECONDS]' => 'grapheneDefaultMinerPayVestingSeconds',
                '[GRAPHENE_MAX_INTEREST_APR]' => 'grapheneMaxInterestApr',
                '[GRAPHENE_MINER_ACCOUNT]' => 'grapheneMinerAccount',
                '[GRAPHENE_NULL_ACCOUNT]' => 'grapheneNullAccount',
                '[GRAPHENE_TEMP_ACCOUNT]' => 'grapheneTempAccount'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawConfig, $path);
            self::getPropertyAccessor()->setValue($config, $modelPath, $value);
        }

        return $config;
    }
}