<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\General\FeeParameter;
use DCorePHP\Model\General\FeeScheduleParameters;
use DCorePHP\Model\General\GlobalProperty;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetGlobalProperties extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(
            'database',
            'get_global_properties'
        );
    }

    public static function responseToModel(BaseResponse $response): GlobalProperty
    {
        $rawGlobalProps = $response->getResult();
        $globalProps = new GlobalProperty();

        foreach (
            [
                '[id]' => 'id',
                '[parameters][current_fees][scale]' => 'parameters.fees.scale',
                '[parameters][block_interval]' => 'parameters.blockInterval',
                '[parameters][maintenance_interval]' => 'parameters.maintenanceInterval',
                '[parameters][maintenance_skip_slots]' => 'parameters.maintenanceSkipSlots',
                '[parameters][miner_proposal_review_period]' => 'parameters.minerProposalReviewPeriod',
                '[parameters][maximum_transaction_size]' => 'parameters.maximumTransactionSize',
                '[parameters][maximum_block_size]' => 'parameters.maximumBlockSize',
                '[parameters][maximum_time_until_expiration]' => 'parameters.maximumTimeUntilExpiration',
                '[parameters][maximum_proposal_lifetime]' => 'parameters.maximumProposalLifetime',
                '[parameters][maximum_asset_feed_publishers]' => 'parameters.maximumAssetFeedPublishers',
                '[parameters][maximum_miner_count]' => 'parameters.maximumMinerCount',
                '[parameters][maximum_authority_membership]' => 'parameters.maximumAuthorityMembership',
                '[parameters][cashback_vesting_period_seconds]' => 'parameters.cashBackVestingPeriodSeconds',
                '[parameters][cashback_vesting_threshold]' => 'parameters.cashBackVestingThreshold',
                '[parameters][max_predicate_opcode]' => 'parameters.maxPredicateOpCode',
                '[parameters][max_authority_depth]' => 'parameters.maxAuthorityDepth',
                '[parameters][extensions]' => 'parameters.extensions',
                '[next_available_vote_id]' => 'nextAvailableVoteId',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawGlobalProps, $path);
            self::getPropertyAccessor()->setValue($globalProps, $modelPath, $value);
        }

        $parameters = [];
        $rawFeeParameters = $rawGlobalProps['parameters']['current_fees']['parameters'];
        foreach ($rawFeeParameters as $rawFeeParameter)
        {
            $parameters[] = (new FeeScheduleParameters())
                ->setOperationType($rawFeeParameter[0])
                ->setFeeParameter((new FeeParameter())
                    ->setPricePerKb($rawFeeParameter[1]['price_per_kbyte'] ?: 0)
                    ->setFee((new AssetAmount())
                        ->setAmount($rawFeeParameter[1]['fee'] ?? $rawFeeParameter[1]['basic_fee'])));
        }
        $globalProps->getParameters()->getFees()->setParameters($parameters);

        $activeMiners = [];
        $rawActiveMiners = $rawGlobalProps['active_miners'];
        foreach ($rawActiveMiners as $rawActiveMiner)
        {
            $activeMiners[] = new ChainObject($rawActiveMiner);
        }
        $globalProps->setActiveMiners($activeMiners);

        return $globalProps;
    }
}