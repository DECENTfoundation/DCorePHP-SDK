<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetTransactionsById extends BaseRequest
{

    public function __construct(array $trxIds)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_objects',
            [array_map(function (ChainObject $trxId) { return $trxId->getId(); }, $trxIds)]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return GetAccountHistory::responseToModel($response);
    }
}
