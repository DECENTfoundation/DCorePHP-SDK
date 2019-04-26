<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class BroadcastTransaction extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            'broadcast',
            'broadcast_transaction',
            [$transaction->toArray()]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return null;
    }
}