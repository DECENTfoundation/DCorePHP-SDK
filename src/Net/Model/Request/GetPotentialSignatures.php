<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetPotentialSignatures extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_potential_signatures',
            [$transaction->toArray()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}