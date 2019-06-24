<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetNftsBySymbol extends GetNftAbstract
{
    public function __construct(array $symbols)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_non_fungible_tokens_by_symbols',
            [$symbols]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $nfts = [];
        foreach ($response->getResult() as $rawNft) {
            if ($rawNft === null) {
                $nfts[] = null;
                continue;
            }
            $nfts[] = parent::resultToModel($rawNft);
        }

        return $nfts;
    }
}