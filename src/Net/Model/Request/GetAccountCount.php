<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountCount extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'database',
            'get_account_count',
            []
        );
    }

    public static function responseToModel(BaseResponse $response): int
    {
        return (int)$response->getResult();
    }
}
