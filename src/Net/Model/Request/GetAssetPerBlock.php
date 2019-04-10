<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetAssetPerBlock extends BaseRequest
{
    public function __construct(string $blockNum)
    {
        parent::__construct(
            'database',
            'get_asset_per_block_by_block_num',
            [$blockNum]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}