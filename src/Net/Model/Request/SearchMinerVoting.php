<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Mining\MinerVotingInfo;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchMinerVoting extends BaseRequest
{
    public const NAME_ASC = '+name';
    public const NAME_DESC = '-name';

    public function __construct($searchTerm, $order, ?ChainObject $id, $accountName, $onlyMyVotes, $limit)
    {
        parent::__construct(
            'database',
            'search_miner_voting',
            [$accountName, $searchTerm, $onlyMyVotes, $order, $id ? $id->getId() : null, $limit]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $minersInfo = [];
        foreach ($response->getResult() as $rawInfo) {
            $minerInfo = new MinerVotingInfo();
            foreach (
                [
                    '[id]' => 'id',
                    '[name]' => 'name',
                    '[url]' => 'url',
                    '[total_votes]' => 'votes',
                    '[voted]' => 'voted'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawInfo, $path);
                self::getPropertyAccessor()->setValue($minerInfo, $modelPath, $value);
            }
            $minersInfo[] = $minerInfo;
        }
        return $minersInfo;
    }
}