<?php

namespace DCorePHP\Net\Model\Request;

class GetTransaction extends GetTransactionAbstract
{
    public function __construct(string $blockNum, string $trxInBlock)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_transaction',
            [$blockNum, $trxInBlock]
        );
    }
}
