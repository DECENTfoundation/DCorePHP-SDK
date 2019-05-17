<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListPublishingManagers extends BaseRequest
{

    public function __construct(string $lowerBound, int $limit)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'list_publishing_managers',
            [$lowerBound, $limit]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        // TODO: Untested no data
        dump('here');
        dump($response->getResult());
    }
}