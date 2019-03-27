<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class SearchFeedback extends BaseRequest
{

    public function __construct(string $uri, string $user, string $startId, int $count)
    {
        parent::__construct(
            'database',
            'search_feedback',
            [$user, $uri, $startId, $count]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        // TODO: Missing data
        return [];
    }
}