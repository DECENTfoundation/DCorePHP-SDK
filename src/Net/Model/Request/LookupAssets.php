<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class LookupAssets extends GetAssetAbstract
{
    public function __construct(array $assetSymbols)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'lookup_asset_symbols',
            [$assetSymbols]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $assets = [];
        foreach ($response->getResult() as $rawAsset) {
            $assets[] = parent::resultToModel($rawAsset);
        }

        return $assets;
    }
}