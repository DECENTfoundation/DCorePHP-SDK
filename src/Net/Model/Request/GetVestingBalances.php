<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetVestingBalances extends BaseRequest
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            'database',
            'get_vesting_balances',
            [$accountId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Missing test data
//        dump($response->getResult());
    }
}