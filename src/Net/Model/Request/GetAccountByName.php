<?php

namespace DCorePHP\Net\Model\Request;

class GetAccountByName extends GetAccount
{
    public function __construct(string $name)
    {
        parent::__construct(
            'database',
            'get_account_by_name',
            [$name]
        );
    }
}
