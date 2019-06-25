<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAsset extends GetAssetAbstract
{

    public function __construct(ChainObject $assetId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_assets',
            [[$assetId->getId()]]
        );
    }

    public static function responseToModel(BaseResponse $response): Asset
    {
        $rawAsset = $response->getResult();
        $response->setResult(reset($rawAsset));

        return parent::responseToModel($response);
    }
}
