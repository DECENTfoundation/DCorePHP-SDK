<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Account;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchAccountHistory extends BaseRequest
{
    public const ORDER_TYPE_ASC = '+type';
    public const ORDER_TO_ASC = '+to';
    public const ORDER_FROM_ASC = '+from';
    public const ORDER_PRICE_ASC = '+price';
    public const ORDER_FEE_ASC = '+fee';
    public const ORDER_DESCRIPTION_ASC = '+description';
    public const ORDER_TIME_ASC = '+time';
    public const ORDER_TYPE_DESC = '-type';
    public const ORDER_TO_DESC = '-to';
    public const ORDER_FROM_DESC = '-from';
    public const ORDER_PRICE_DESC = '-price';
    public const ORDER_FEE_DESC = '-fee';
    public const ORDER__DESCRIPTION_DESC = '-description';
    public const ORDER_TIME_DESC = '-time';

    /**
     * @param ChainObject $accountId
     * @param string $order
     * @param string $startObjectId
     * @param int $limit
     */
    public function __construct(ChainObject $accountId, string $order = self::ORDER_TIME_DESC, string $startObjectId = '0.0.0', int $limit = 100)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'search_account_history',
            [$accountId->getId(), $order, $startObjectId, $limit]
        );
    }

    /**
     * @param BaseResponse $response
     * @return Account[]
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $transactionDetails = [];
        foreach ($response->getResult() as $rawTransaction) {
            $transactionDetail = new TransactionDetail();

            foreach (
                [
                    '[id]' => 'id',
                    '[m_from_account]' => 'from',
                    '[m_to_account]' => 'to',
                    '[m_operation_type]' => 'type',
                    '[m_transaction_amount][amount]' => 'amount.amount',
                    '[m_transaction_amount][asset_id]' => 'amount.assetId',
                    '[m_transaction_fee][amount]' => 'fee.amount',
                    '[m_transaction_fee][asset_id]' => 'fee.assetId',
                    '[m_str_description]' => 'description',
                    '[m_timestamp]' => 'timestamp',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawTransaction, $path);
                self::getPropertyAccessor()->setValue($transactionDetail, $modelPath, $value);
            }

            $transactionDetails[] = $transactionDetail;
        }

        return $transactionDetails;
    }
}
