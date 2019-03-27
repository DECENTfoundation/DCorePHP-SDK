<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListAssets extends GetAssetAbstract
{
    public function __construct(string $lowerBound, int $limit)
    {
        parent::__construct(
            'database',
            'list_assets',
            [$lowerBound, $limit]
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