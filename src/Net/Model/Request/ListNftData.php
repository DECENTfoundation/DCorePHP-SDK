<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class ListNftData extends GetNftDataAbstract
{
    public function __construct(ChainObject $nftId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'list_non_fungible_token_data',
            [$nftId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $nfts = [];
        foreach ($response->getResult() as $rawNft) {
            $nfts[] = parent::resultToModel($rawNft);
        }
        return $nfts;
    }
}