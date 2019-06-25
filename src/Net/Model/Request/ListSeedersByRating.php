<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListSeedersByRating extends GetSeederAbstract
{

    public function __construct(int $count)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'list_seeders_by_rating',
            [$count]
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