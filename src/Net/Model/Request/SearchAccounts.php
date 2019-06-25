<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Account;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchAccounts extends GetAccount
{
    public const ORDER_NAME_ASC = '+name';
    public const ORDER_ID_ASC = '+id';
    public const ORDER_NAME_DESC = '-name';
    public const ORDER_ID_DESC = '-id';
    public const ORDER_NONE = '';

    /**
     * @param string $term
     * @param string $order
     * @param string $startObjectId
     * @param int $limit
     */
    public function __construct(string $term = '', string $order = self::ORDER_NONE, string $startObjectId = '0.0.0', int $limit = 100)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'search_accounts',
            [$term, $order, $startObjectId, $limit]
        );
    }

    /**
     * @param BaseResponse $response
     * @return Account[]
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $accounts = [];
        foreach ($response->getResult() as $rawAccount) {
            $accounts[] = self::resultToModel($rawAccount);
        }

        return $accounts;
    }
}
