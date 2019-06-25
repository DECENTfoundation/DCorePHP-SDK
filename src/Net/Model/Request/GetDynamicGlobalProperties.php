<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetDynamicGlobalProperties extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_dynamic_global_properties'
        );
    }

    public static function responseToModel(BaseResponse $response): DynamicGlobalProps
    {
        $dynamicGlobalProps = new DynamicGlobalProps();

        foreach (
            [
                '[id]' => 'id',
                '[head_block_number]' => 'headBlockNumber',
                '[head_block_id]' => 'headBlockId',
                '[time]' => 'time',
                '[current_miner]' => 'currentMiner',
                '[next_maintenance_time]' => 'nextMaintenanceTime',
                '[last_budget_time]' => 'lastBudgetTime',
                '[unspent_fee_budget]' => 'unspentFeeBudget',
                '[mined_rewards]' => 'minedRewards',
                '[miner_budget_from_fees]' => 'minerBudgetFromFees',
                '[miner_budget_from_rewards]' => 'minerBudgetFromRewards',
                '[accounts_registered_this_interval]' => 'accountsRegisteredThisInterval',
                '[recently_missed_count]' => 'recentlyMissedCount',
                '[current_aslot]' => 'currentAslot',
                '[recent_slots_filled]' => 'recentSlotsFilled',
                '[dynamic_flags]' => 'dynamicFlags',
                '[last_irreversible_block_num]' => 'lastIrreversibleBlockNum',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($response->getResult(), $path);
            self::getPropertyAccessor()->setValue($dynamicGlobalProps, $modelPath, $value);
        }

        return $dynamicGlobalProps;
    }
}
