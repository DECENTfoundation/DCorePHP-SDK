<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;

class GetSeeder extends GetSeederAbstract
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            'database',
            'get_seeder',
            [$accountId->getId()]
        );
    }
}