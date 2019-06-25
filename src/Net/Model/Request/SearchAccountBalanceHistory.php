<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchAccountBalanceHistory extends GetAccountBalanceAbstract
{
    /**
     * SearchAccountBalanceHistory constructor.
     * @param ChainObject $accountId
     * @param Asset[] $assets
     * @param ChainObject $recipientAccount
     * @param string $fromBlock
     * @param string $toBlock
     * @param string $startOffset
     * @param int $limit
     */
    public function __construct(
        ChainObject $accountId,
        array $assets,
        ?ChainObject $recipientAccount,
        string $fromBlock,
        string $toBlock,
        string $startOffset,
        int $limit
    ) {
        parent::__construct(
            self::API_GROUP_HISTORY,
            'search_account_balance_history',
            [$accountId->getId(), array_map(function(Asset $asset) { return $asset->getId(); }, $assets), $recipientAccount, $fromBlock, $toBlock, $startOffset, $limit]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $balances = [];
        foreach ($response->getResult() as $rawBalance) {
            $balances[] = parent::resultToModel($rawBalance);
        }
        return $balances;
    }
}