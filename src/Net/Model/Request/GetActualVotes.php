<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\MinerVotes;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetActualVotes extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_actual_votes'
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $rawVotes = $response->getResult();
        $votes = [];
        foreach ($rawVotes as $rawVote) {
            $minerVotes = new MinerVotes();
            foreach (
                [
                    '[account_name]' => 'account',
                    '[votes]' => 'votes'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawVote, $path);
                self::getPropertyAccessor()->setValue($minerVotes, $modelPath, $value);
            }
            $votes[] = $minerVotes;
        }
        return $votes;
    }
}