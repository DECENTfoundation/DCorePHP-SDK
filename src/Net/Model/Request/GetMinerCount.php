<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetMinerCount extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'database',
            'get_miner_count'
        );
    }

    public static function responseToModel(BaseResponse $response): string
    {
        return $response->getResult();
    }
}