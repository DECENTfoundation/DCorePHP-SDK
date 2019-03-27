<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class LookupAssets extends BaseRequest
{
    public function __construct(array $assetSymbols)
    {
        parent::__construct(
            'database',
            'lookup_asset_symbols',
            [$assetSymbols]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Implement responseToModel() method.
        dump($response->getResult());
    }
}