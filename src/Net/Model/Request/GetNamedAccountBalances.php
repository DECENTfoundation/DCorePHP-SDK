<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetNamedAccountBalances extends BaseRequest
{
    public function __construct(string $name, array $assets)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_named_account_balances',
            [$name, array_map(function(ChainObject $asset) {return $asset->getId();}, $assets)]
        );
    }

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