<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;

class GetSeeder extends GetSeederAbstract
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_seeder',
            [$accountId->getId()]
        );
    }
}