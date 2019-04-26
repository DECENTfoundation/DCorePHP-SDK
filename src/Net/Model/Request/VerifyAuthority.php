<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class VerifyAuthority extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            'database',
            'verify_authority',
            [$transaction->toArray()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}