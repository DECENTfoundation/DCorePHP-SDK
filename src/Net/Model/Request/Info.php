<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class Info extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'info'
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}