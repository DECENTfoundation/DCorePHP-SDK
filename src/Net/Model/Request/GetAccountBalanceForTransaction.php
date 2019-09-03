<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountBalanceForTransaction extends GetAccountBalanceAbstract
{
    public function __construct(ChainObject $accountId, ChainObject $operationId)
    {
        parent::__construct(
            self::API_GROUP_HISTORY,
            'get_account_balance_for_transaction',
            [$accountId->getId(), $operationId->getId()]
        );
    }
}