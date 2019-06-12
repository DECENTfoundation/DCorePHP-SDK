<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetData;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAssetData extends BaseRequest
{
    public function __construct(array $assetIds)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_objects',
            [array_map(function(ChainObject $asset) {return $asset->getId();}, $assetIds)]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $rawAssets = $response->getResult();
        $data = [];

        foreach ($rawAssets as $rawAsset) {
            $assetData = new AssetData();

            foreach (
                [
                    '[id]' => 'id',
                    '[current_supply]' => 'currentSupply',
                    '[asset_pool]' => 'assetPool',
                    '[core_pool]' => 'corePool'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawAsset, $path);
                self::getPropertyAccessor()->setValue($assetData, $modelPath, $value);
            }
            $data[] = $assetData;
        }

        return $data;
    }
}