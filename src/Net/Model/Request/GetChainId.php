<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetChainId extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'database',
            'get_chain_id'
        );
    }

    public static function responseToModel(BaseResponse $response): string
    {
        return $response->getResult();
    }
}