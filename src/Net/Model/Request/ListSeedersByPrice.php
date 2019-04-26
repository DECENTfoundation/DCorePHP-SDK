<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListSeedersByPrice extends GetSeederAbstract
{

    public function __construct(int $count)
    {
        parent::__construct(
            'database',
            'list_seeders_by_price',
            [$count]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $seeders = [];
        foreach ($response->getResult() as $rawSeeder) {
            $seeders[] = self::resultToModel($rawSeeder);
        }

        return $seeders;
    }
}