<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetAssetAbstract extends BaseRequest
{

    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result): Asset
    {
        $asset = new Asset();
        foreach (
            [
                '[id]' => 'id',
                '[symbol]' => 'symbol',
                '[precision]' => 'precision',
                '[issuer]' => 'issuer',
                '[description]' => 'description',
                '[options][max_supply]' => 'options.maxSupply',
                '[options][core_exchange_rate][base][amount]' => 'options.exchangeRate.base.amount',
                '[options][core_exchange_rate][base][asset_id]' => 'options.exchangeRate.base.assetId',
                '[options][core_exchange_rate][quote][amount]' => 'options.exchangeRate.quote.amount',
                '[options][core_exchange_rate][quote][asset_id]' => 'options.exchangeRate.quote.assetId',
                '[options][is_exchangeable]' => 'options.exchangeable',
                '[options][extensions]' => 'options.extensions',
                '[dynamic_asset_data_id]' => 'dataId',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($asset, $modelPath, $value);
        }
        return $asset;
    }
}