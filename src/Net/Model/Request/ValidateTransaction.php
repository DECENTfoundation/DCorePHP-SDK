<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Transaction;

class ValidateTransaction extends GetTransactionAbstract
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'validate_transaction',
            [$transaction->toArray()]
        );
    }
}