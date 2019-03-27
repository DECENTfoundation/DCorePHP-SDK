<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Net\Model\Response\BaseResponse;

class LookupMinerAccounts extends BaseRequest
{
    public function __construct(string $lowerBound, int $limit)
    {
        parent::__construct(
            'database',
            'lookup_miner_accounts',
            [$lowerBound, $limit]
        );
    }

    /**
     * @param BaseResponse $response
     * @return array
     * @throws \DCorePHP\Exception\ValidationException
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $minderIds = [];
        $rawMinderIds = $response->getResult();
        foreach ($rawMinderIds as [$name, $id]) {
            $minerId = (new MinerId())->setId($id)->setName($name);
            $minderIds[] = $minerId;
        }
        return $minderIds;
    }
}