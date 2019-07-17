<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class ListNfts extends GetNftAbstract
{
    public function __construct(string $lowerBound, int $limit)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'list_non_fungible_tokens',
            [$lowerBound, $limit]
        );
    }
    public static function responseToModel(BaseResponse $response)
    {
        $nfts = [];
        foreach ($response->getResult() as $result) {
            $nfts[] = self::resultToModel($result);
        }
        return $nfts;
    }
}