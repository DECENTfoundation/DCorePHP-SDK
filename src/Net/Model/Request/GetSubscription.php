<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;

class GetSubscription extends GetSubscriptionAbstract
{
    public function __construct(ChainObject $id)
    {
        parent::__construct(
            'database',
            'get_subscription',
            [$id->getId()]
        );
    }
}