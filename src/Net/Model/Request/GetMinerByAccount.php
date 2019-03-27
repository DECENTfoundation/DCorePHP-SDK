<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetMinerByAccount extends GetMinerAbstract
{
    public function __construct(ChainObject $account)
    {
        parent::__construct(
            'database',
            'get_miner_by_account',
            [$account->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response): Miner
    {
        return self::resultToModel($response->getResult());
    }
}