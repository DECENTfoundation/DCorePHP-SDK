<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Content\Seeder;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetSeederAbstract extends BaseRequest
{

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult() ? self::resultToModel($response->getResult()) : null;
    }

    protected static function resultToModel(array $result): Seeder
    {

        $seeder = new Seeder();

        foreach (
            [
                '[id]' => 'id',
                '[seeder]' => 'seeder',
                '[free_space]' => 'freeSpace',
                '[price][amount]' => 'price.amount',
                '[price][asset_id]' => 'price.assetId',
                '[expiration]' => 'expiration',
                '[pubKey][s]' => 'pubKey.s',
                '[ipfs_ID]' => 'ipfsId',
                '[stats]' => 'stats',
                '[rating]' => 'rating',
                '[region_code]' => 'regionCode',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($seeder, $modelPath, $value);
        }

        return $seeder;

    }
}