<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetRealSupply extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_real_supply'
        );
    }

    public static function responseToModel(BaseResponse $response): RealSupply
    {
        $rawRealSupply = $response->getResult();
        $realSupply = new RealSupply();

        foreach (
            [
                '[account_balances]' => 'accountBalances',
                '[vesting_balances]' => 'vestingBalances',
                '[escrows]' => 'escrows',
                '[pools]' => 'pools',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawRealSupply, $path);
            self::getPropertyAccessor()->setValue($realSupply, $modelPath, $value);
        }

        return $realSupply;
    }
}