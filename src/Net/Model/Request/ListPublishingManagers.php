<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
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
        $result = [];
        foreach($response->getResult() as $rawId) {
            $result[] = new ChainObject($rawId);
        }
        return $result;
    }
}