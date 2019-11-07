<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\General\Config;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetConfig extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_configuration'
        );
    }

    public static function responseToModel(BaseResponse $response): Config
    {
        $rawConfig = $response->getResult();
        $config = new Config();
        foreach (
            [
                '[graphene_symbol]' => 'grapheneSymbol',
                '[graphene_address_prefix]' => 'grapheneAddressPrefix',
                '[graphene_min_account_name_length]' => 'grapheneMinAccountNameLength',
                '[graphene_max_account_name_length]' => 'grapheneMaxAccountNameLength',
                '[graphene_min_asset_symbol_length]' => 'grapheneMinAssetSymbolLength',
                '[graphene_max_asset_symbol_length]' => 'grapheneMaxAssetSymbolLength',
                '[graphene_max_share_supply]' => 'grapheneMaxShareSupply',
                '[graphene_max_pay_rate]' => 'grapheneMaxPayRate',
                '[graphene_max_sig_check_depth]' => 'grapheneMaxSigCheckDepth',
                '[graphene_min_transaction_size_limit]' => 'grapheneMinTransactionSizeLimit',
                '[graphene_min_block_interval]' => 'grapheneMinBlockInterval',
                '[graphene_max_block_interval]' => 'grapheneMaxBlockInterval',
                '[graphene_default_block_interval]' => 'grapheneDefaultBlockInterval',
                '[graphene_default_max_transaction_size]' => 'grapheneDefaultMaxTransactionSize',
                '[graphene_default_max_block_size]' => 'grapheneDefaultMaxBlockSize',
                '[graphene_default_max_time_until_expiration]' => 'grapheneDefaultMaxTimeUntilExpiration',
                '[graphene_default_maintenance_interval]' => 'grapheneDefaultMaintenanceInterval',
                '[graphene_default_maintenance_skip_slots]' => 'grapheneDefaultMaintenanceSkipSlots',
                '[graphene_min_undo_history]' => 'grapheneMinUndoHistory',
                '[graphene_max_undo_history]' => 'grapheneMaxUndoHistory',
                '[graphene_min_block_size_limit]' => 'grapheneMinBlockSizeLimit',
                '[graphene_min_transaction_expiration_limit]' => 'grapheneMinTransactionExpirationLimit',
                '[graphene_blockchain_precision]' => 'grapheneBlockchainPrecision',
                '[graphene_blockchain_precision_digits]' => 'grapheneBlockchainPrecisionDigits',
                '[graphene_default_transfer_fee]' => 'grapheneDefaultTransferFee',
                '[graphene_max_instance_id]' => 'grapheneMaxInstanceId',
                '[graphene_100_percent]' => 'graphene100Percent',
                '[graphene_1_percent]' => 'graphene1Percent',
                '[graphene_max_market_fee_percent]' => 'grapheneMaxMarketFeePercent',
                '[graphene_default_force_settlement_delay]' => 'grapheneDefaultForceSettlementDelay',
                '[graphene_default_force_settlement_offset]' => 'grapheneDefaultForceSettlementOffset',
                '[graphene_default_force_settlement_max_volume]' => 'grapheneDefaultForceSettlementMaxVolume',
                '[graphene_default_price_feed_lifetime]' => 'grapheneDefaultPriceFeedLifetime',
                '[graphene_max_feed_producers]' => 'grapheneMaxFeedProducers',
                '[graphene_default_max_authority_membership]' => 'grapheneDefaultMaxAuthorityMembership',
                '[graphene_default_max_asset_whitelist_authorities]' => 'grapheneDefaultMaxAssetWhitelistAuthorities',
                '[graphene_default_max_asset_feed_publishers]' => 'grapheneDefaultMaxAssetFeedPublishers',
                '[graphene_collateral_ratio_denom]' => 'grapheneCollateralRatioDenom',
                '[graphene_min_collateral_ratio]' => 'grapheneMinCollateralRatio',
                '[graphene_max_collateral_ratio]' => 'grapheneMaxCollateralRatio',
                '[graphene_default_maintenance_collateral_ratio]' => 'grapheneDefaultMaintenanceCollateralRatio',
                '[graphene_default_max_short_squeeze_ratio]' => 'grapheneDefaultMaxShortSqueezeRatio',
                '[graphene_default_margin_period_sec]' => 'grapheneDefaultMarginPeriodSec',
                '[graphene_default_max_miners]' => 'grapheneDefaultMaxMiners',
                '[graphene_default_max_proposal_lifetime_sec]' => 'grapheneDefaultMaxProposalLifetimeSec',
                '[graphene_default_miner_proposal_review_period_sec]' => 'grapheneDefaultMinerProposalReviewPeriodSec',
                '[graphene_default_network_percent_of_fee]' => 'grapheneDefaultNetworkPercentOfFee',
                '[graphene_default_lifetime_referrer_percent_of_fee]' => 'grapheneDefaultLifetimeReferrerPercentOfFee',
                '[graphene_default_max_bulk_discount_percent]' => 'grapheneDefaultMaxBulkDiscountPercent',
                '[graphene_default_bulk_discount_threshold_min]' => 'grapheneDefaultBulkDiscountThresholdMin',
                '[graphene_default_bulk_discount_threshold_max]' => 'grapheneDefaultBulkDiscountThresholdMax',
                '[graphene_default_cashback_vesting_period_sec]' => 'grapheneDefaultCashbackVestingPeriodSec',
                '[graphene_default_cashback_vesting_threshold]' => 'grapheneDefaultCashbackVestingThreshold',
                '[graphene_default_burn_percent_of_fee]' => 'grapheneDefaultBurnPercentOfFee',
                '[graphene_miner_pay_percent_precision]' => 'grapheneMinerPayPercentPrecision',
                '[graphene_default_max_assert_opcode]' => 'grapheneDefaultMaxAssertOpcode',
                '[graphene_default_fee_liquidation_threshold]' => 'grapheneDefaultFeeLiquidationThreshold',
                '[graphene_default_accounts_per_fee_scale]' => 'grapheneDefaultAccountsPerFeeScale',
                '[graphene_default_account_fee_scale_bitshifts]' => 'grapheneDefaultAccountFeeScaleBitshifts',
                '[graphene_max_worker_name_length]' => 'grapheneMaxWorkerNameLength',
                '[graphene_max_url_length]' => 'grapheneMaxUrlLength',
                '[graphene_near_schedule_ctr_iv]' => 'grapheneNearScheduleCtrIv',
                '[graphene_far_schedule_ctr_iv]' => 'grapheneFarScheduleCtrIv',
                '[graphene_core_asset_cycle_rate]' => 'grapheneCoreAssetCycleRate',
                '[graphene_core_asset_cycle_rate_bits]' => 'grapheneCoreAssetCycleRateBits',
                '[graphene_default_miner_pay_per_block]' => 'grapheneDefaultMinerPayPerBlock',
                '[graphene_default_miner_pay_vesting_seconds]' => 'grapheneDefaultMinerPayVestingSeconds',
                '[graphene_max_interest_apr]' => 'grapheneMaxInterestApr',
                '[graphene_miner_account]' => 'grapheneMinerAccount',
                '[graphene_null_account]' => 'grapheneNullAccount',
                '[graphene_temp_account]' => 'grapheneTempAccount'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawConfig, $path);
            self::getPropertyAccessor()->setValue($config, $modelPath, $value);
        }

        return $config;
    }
}
