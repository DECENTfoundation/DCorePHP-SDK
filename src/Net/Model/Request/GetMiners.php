<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetMiners extends GetMinerAbstract
{
    public function __construct(array $minderIds)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_objects',
            [array_map(function (ChainObject $miner) { return $miner->getId(); }, $minderIds)]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $miners = [];
        foreach ($response->getResult() as $rawMiner) {
            $miners[] = parent::resultToModel($rawMiner);
        }
        return $miners;
    }
}