<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetProposedTransactions extends BaseRequest
{
    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_proposed_transactions',
            [$accountId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: No data
        dump($response->getResult());
    }
}