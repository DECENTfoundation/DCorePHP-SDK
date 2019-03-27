<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountsById extends GetAccount
{
    public function __construct(array $accountIds)
    {
        parent::__construct(
            'database',
            'get_objects',
            [array_map(function(ChainObject $accountId) { return $accountId->getId(); }, $accountIds)]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $accounts = [];
        foreach ($response->getResult() as $rawAccount) {
            $accounts[] = parent::resultToModel($rawAccount);
        }
        return $accounts;
    }
}