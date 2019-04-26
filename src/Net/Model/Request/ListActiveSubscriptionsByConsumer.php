<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class ListActiveSubscriptionsByConsumer extends GetSubscriptionAbstract
{
    public function __construct(ChainObject $consumer, int $count)
    {
        parent::__construct(
            'database',
            'list_active_subscriptions_by_consumer',
            [$consumer->getId(), $count]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $subscriptions = [];
        foreach ($response->getResult() as $rawSubscription) {
            $subscriptions[] = self::resultToModel($rawSubscription);
        }

        return $subscriptions;
    }
}