<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountBalances extends BaseRequest
{
    public function __construct(ChainObject $id, array $assets)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_account_balances',
            [$id->getId(), array_map(function(ChainObject $asset) {return $asset->getId();}, $assets)]
        );
    }

    /**
     * @param BaseResponse $response
     * @return AssetAmount[]
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $assets = [];
        foreach ($response->getResult() as $rawAsset) {
            $asset = new AssetAmount();

            foreach (
                [
                    '[asset_id]' => 'assetId',
                    '[amount]' => 'amount',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawAsset, $path);
                self::getPropertyAccessor()->setValue($asset, $modelPath, $value);
            }

            $assets[] = $asset;
        }

        return $assets;
    }
}
