<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetOpenBuyings extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_open_buyings'
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        // TODO
        dump('responseToModel in GetOpenBuyings');
        dump($response);

        return [];
    }
}