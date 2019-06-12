<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;

class GetBuyingByUri extends GetPurchase
{

    public function __construct(ChainObject $accountId, string $uri)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_buying_by_consumer_URI',
            [$accountId->getId(), $uri]
        );
    }
}