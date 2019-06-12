<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;

class GetSubscription extends GetSubscriptionAbstract
{
    public function __construct(ChainObject $id)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_subscription',
            [$id->getId()]
        );
    }
}