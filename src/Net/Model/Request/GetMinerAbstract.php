<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetMinerAbstract extends BaseRequest
{
    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result)
    {
        $miner = new Miner();
        foreach (
            [
                '[id]' => 'id',
                '[miner_account]' => 'minerAccount',
                '[last_aslot]' => 'lastAslot',
                '[signing_key]' => 'signingKey',
                '[pay_vb]' => 'payVb',
                '[vote_id]' => 'voteId',
                '[total_votes]' => 'totalVotes',
                '[url]' => 'url',
                '[total_missed]' => 'totalMissed',
                '[last_confirmed_block_num]' => 'lastConfirmedBlockNum',
                '[vote_ranking]' => 'voteRanking'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($miner, $modelPath, $value);
        }

        return $miner;
    }
}