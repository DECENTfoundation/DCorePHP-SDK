<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetOpenBuyingsByConsumer extends BaseRequest
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            'database',
            'get_open_buyings_by_consumer',
            [$accountId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Missing Data
        dump($response->getResult());
    }
}