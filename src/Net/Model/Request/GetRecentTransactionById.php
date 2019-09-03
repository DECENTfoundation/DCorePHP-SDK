<?php

namespace DCorePHP\Net\Model\Request;

class GetRecentTransactionById extends GetTransactionAbstract
{
    public function __construct(string $trxId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_recent_transaction_by_id',
            [$trxId]
        );
    }
}