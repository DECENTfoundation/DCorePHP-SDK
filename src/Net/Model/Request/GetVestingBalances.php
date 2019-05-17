<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\VestingBalance;
use DCorePHP\Model\Explorer\VestingPolicy;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetVestingBalances extends BaseRequest
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_vesting_balances',
            [$accountId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $vestingBalances = [];
        foreach ($response->getResult() as $rawVestingBalance) {
            $vestingBalance = new VestingBalance();
            foreach (
                [
                    '[id]' => 'id',
                    '[owner]' => 'owner',
                    '[balance][amount]' => 'balance.amount',
                    '[balance][asset_id]' => 'balance.assetId',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawVestingBalance, $path);
                self::getPropertyAccessor()->setValue($vestingBalance, $modelPath, $value);
            }

            $vestingBalance->setPolicyNumber($rawVestingBalance['policy'][0]);

            $rawPolicy = $rawVestingBalance['policy'][1];
            $policy = new VestingPolicy();
            foreach (
                [
                    '[vesting_seconds]' => 'vestingSeconds',
                    '[start_claim]' => 'startClaim',
                    '[coin_seconds_earned]' => 'coinSecondsEarned',
                    '[coin_seconds_earned_last_update]' => 'coinSecondsEarnedLastUpdate',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawPolicy, $path);
                self::getPropertyAccessor()->setValue($policy, $modelPath, $value);
            }
            $vestingBalance->setPolicy($policy);

            $vestingBalances[] = $vestingBalance;
        }
        return $vestingBalances;
    }
}