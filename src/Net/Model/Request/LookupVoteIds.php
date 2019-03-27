<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class LookupVoteIds extends GetMinerAbstract
{
    public function __construct(array $voteIds)
    {
        parent::__construct(
            'database',
            'lookup_vote_ids',
            [$voteIds]
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