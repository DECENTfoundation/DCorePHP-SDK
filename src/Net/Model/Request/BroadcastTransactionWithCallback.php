<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class BroadcastTransactionWithCallback extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            'database',
            'broadcast_transaction_with_callback',
            [6, $transaction->toArray()]
        );
    }

    /**
     * @param BaseResponse $response
     * @return null
     */
    public static function responseToModel(BaseResponse $response)
    {
        return null;
    }
}
