<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Content\Purchase;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetPurchase extends BaseRequest
{

    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result): Purchase
    {
        $purchase = new Purchase();
        foreach (
            [
                '[id]' => 'id',
                '[consumer]' => 'consumer',
                '[URI]' => 'uri',
                '[price][amount]' => 'price.amount',
                '[price][asset_id]' => 'price.assetId',
                '[paid_price_before_exchange][amount]' => 'paidPriceBeforeExchange.amount',
                '[paid_price_before_exchange][asset_id]' => 'paidPriceBeforeExchange.assetId',
                '[paid_price_after_exchange][amount]' => 'paidPriceAfterExchange.amount',
                '[paid_price_after_exchange][asset_id]' => 'paidPriceAfterExchange.assetId',
                '[seeders_answered]' => 'seedersAnswered',
                '[size]' => 'size',
                '[rating]' => 'rating',
                '[comment]' => 'comment',
                '[expiration_time]' => 'expirationTime',
                '[pubKey][s]' => 'pubKey.s',
                '[key_particles]' => 'keyParticles',
                '[expired]' => 'expired',
                '[delivered]' => 'delivered',
                '[expiration_or_delivery_time]' => 'expirationOrDeliveryTime',
                '[rated_or_commented]' => 'ratedOrCommented',
                '[created]' => 'created',
                '[region_code_from]' => 'regionCodeFrom',
                '[synopsis]' => 'synopsis'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($purchase, $modelPath, $value);
        }

        return $purchase;
    }

}