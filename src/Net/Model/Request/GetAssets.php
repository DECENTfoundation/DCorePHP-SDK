<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAssets extends GetAssetAbstract
{
    public function __construct(array $assetIds)
    {
        parent::__construct(
            'database',
            'get_assets',
            [array_map(function(ChainObject $assetId) {return $assetId->getId(); }, $assetIds)]
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
