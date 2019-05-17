<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class ListActiveSubscriptionsByAuthor extends GetSubscriptionAbstract
{
    public function __construct(ChainObject $author, int $count)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'list_active_subscriptions_by_author',
            [$author->getId(), $count]
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