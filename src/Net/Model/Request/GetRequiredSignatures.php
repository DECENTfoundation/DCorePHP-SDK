<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetRequiredSignatures extends BaseRequest
{
    public function __construct(Transaction $transaction, array $keys)
    {
        parent::__construct(
            'database',
            'get_required_signatures',
            [$transaction->toArray(), array_map(function (Address $key) { return $key->encode(); }, $keys)]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}