<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\General\MinerRewardInput;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetTimeToMaintenance extends BaseRequest
{
    public function __construct(string $time)
    {
        parent::__construct(
            'database',
            'get_time_to_maint_by_block_time',
            [$time]
        );
    }

    public static function responseToModel(BaseResponse $response): MinerRewardInput
    {
        $rawReward = $response->getResult();
        $minerReward = new MinerRewardInput();
        foreach (
            [
                '[time_to_maint]' => 'timeToMaintenance',
                '[from_accumulated_fees]' => 'fromAccumulatedFees',
                '[block_interval]' => 'blockInterval'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawReward, $path);
            self::getPropertyAccessor()->setValue($minerReward, $modelPath, $value);
        }

        return $minerReward;
    }
}