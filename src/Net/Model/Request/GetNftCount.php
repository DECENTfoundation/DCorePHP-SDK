<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetNftCount extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_non_fungible_token_count'
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}