<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class LookupAccountNames extends GetAccount
{
    public function __construct(array $names)
    {
        parent::__construct(
            'database',
            'lookup_account_names',
            [$names]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $accounts = [];
        foreach ($response->getResult() as $account) {
            $accounts[] = parent::resultToModel($account);
        }
        return $accounts;
    }
}