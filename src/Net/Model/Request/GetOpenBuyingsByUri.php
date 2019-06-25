<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetOpenBuyingsByUri extends BaseRequest
{

    public function __construct(string $uri)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_open_buyings_by_URI',
            [$uri]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Missing data
        dump($response->getResult());
    }
}