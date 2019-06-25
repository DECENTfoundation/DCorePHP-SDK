<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetFeedsByMiner extends BaseRequest
{
    public function __construct(ChainObject $account, int $count = 100)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_feeds_by_miner',
            [$account->getId(), $count]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Missing Data
        dump($response->getResult());
    }
}