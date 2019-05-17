<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class Database extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            self::API_GROUP_DATABASE
        );
    }

    /**
     * @param BaseResponse $response
     * @return int result number
     */
    public static function responseToModel(BaseResponse $response): int
    {
        return $response->getResult();
    }
}
