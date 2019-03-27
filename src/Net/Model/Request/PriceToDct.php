<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Net\Model\Response\BaseResponse;

class PriceToDct extends BaseRequest
{
    public function __construct(AssetAmount $amount)
    {
        parent::__construct(
            'database',
            'price_to_dct',
            [$amount->toArray()]
        );
    }

    public static function responseToModel(BaseResponse $response): AssetAmount
    {
        $rawAssetAmount = $response->getResult();
        $assetAmount = new AssetAmount();

        foreach (
            [
                '[amount]' => 'amount',
                '[asset_id]' => 'assetId'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawAssetAmount, $path);
            self::getPropertyAccessor()->setValue($assetAmount, $modelPath, $value);
        }

        return $assetAmount;
    }
}