<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Account;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountById extends GetAccount
{
    public function __construct(ChainObject $id)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_accounts',
            [[$id->getId()]]
        );
    }

    public static function responseToModel(BaseResponse $response): ?Account
    {
        $rawAccounts = $response->getResult();
        $response->setResult(reset($rawAccounts));

        return parent::responseToModel($response);
    }
}
