<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetTransactionHex extends BaseRequest
{
    public function __construct($transaction)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_transaction_hex',
            [$transaction]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}