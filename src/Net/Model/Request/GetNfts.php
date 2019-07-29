<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetNfts extends GetNftAbstract
{
    public function __construct(array $ids)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_non_fungible_tokens',
            [array_map(static function (ChainObject $id) { return $id->getId(); }, $ids)]
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