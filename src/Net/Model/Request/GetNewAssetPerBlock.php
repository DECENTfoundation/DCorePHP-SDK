<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetNewAssetPerBlock extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_new_asset_per_block'
        );
    }

    public static function responseToModel(BaseResponse $response): string
    {
        return $response->getResult();
    }
}