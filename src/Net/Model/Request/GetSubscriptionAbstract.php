<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Subscription\Subscription;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetSubscriptionAbstract extends BaseRequest
{
    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result): Subscription
    {
        $subscription = new Subscription();
        foreach (
            [
                '[id]' => 'id',
                '[from]' => 'from',
                '[to]' => 'to',
                '[expiration]' => 'expiration',
                '[automatic_renewal]' => 'renewal'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($subscription, $modelPath, $value);
        }
        return $subscription;
    }
}