<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Account;
use DCorePHP\Net\Model\Response\BaseResponse;

class ListAccounts extends BaseRequest
{
    public function __construct(string $lowerbound, int $limit)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'lookup_accounts',
            [$lowerbound, $limit]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $accounts = [];
        foreach ($response->getResult() as $rawAccount) {
            $account = new Account();
            $account->setId($rawAccount[1]);
            $account->setName($rawAccount[0]);

            $accounts[] = $account;
        }

        return $accounts;
    }
}