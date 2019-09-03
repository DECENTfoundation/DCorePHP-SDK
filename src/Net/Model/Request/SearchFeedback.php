<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class SearchFeedback extends GetPurchase
{

    public function __construct(string $uri, string $user, string $startId, int $count)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'search_feedback',
            [$user, $uri, $startId, $count]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $feedback = [];
        foreach ($response->getResult() as $result) {
            $feedback[] = self::resultToModel($result);
        }
        return $feedback;
    }
}