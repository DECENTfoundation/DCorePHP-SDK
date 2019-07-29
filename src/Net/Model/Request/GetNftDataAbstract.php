<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\NftData;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetNftDataAbstract extends BaseRequest
{
    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }
    public static function resultToModel(array $result)
    {
        $nftData = new NftData();
        foreach (
            [
                '[id]' => 'id',
                '[nft_id]' => 'nftId',
                '[owner]' => 'owner',
                '[data]' => 'data'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($nftData, $modelPath, $value);
        }
        return $nftData;
    }
}