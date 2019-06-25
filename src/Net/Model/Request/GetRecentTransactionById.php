<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetRecentTransactionById extends BaseRequest
{

    public function __construct(string $trxId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_recent_transaction_by_id',
            [$trxId]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: No Data
        dump($response->getResult());
    }
}