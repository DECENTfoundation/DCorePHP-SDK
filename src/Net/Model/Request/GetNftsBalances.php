<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetNftsBalances extends GetNftDataAbstract
{
    public function __construct(ChainObject $account, array $nftIds)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_non_fungible_token_balances',
            [$account->getId(), array_map(static function (ChainObject $id) { return $id->getId(); }, $nftIds)]
        );
    }
    public static function responseToModel(BaseResponse $response)
    {
        $nfts = [];
        foreach ($response->getResult() as $rawNft) {
            $nfts[] = parent::resultToModel($rawNft);
        }
        return $nfts;
    }
}