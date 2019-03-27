<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListSeedersByRegion extends GetSeederAbstract
{

    public function __construct(string $region)
    {
        parent::__construct(
            'database',
            'list_seeders_by_region',
            [$region]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        // TODO: No data
        $seeders = [];
        foreach ($response->getResult() as $rawSeeder) {
            $seeders[] = self::resultToModel($rawSeeder);
        }

        return $seeders;
    }
}