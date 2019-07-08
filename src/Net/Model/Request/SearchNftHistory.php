<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchNftHistory extends BaseRequest
{
    public function __construct(ChainObject $nftDataId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'search_non_fungible_token_history',
            [$nftDataId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response)
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
                    '[m_nft_data_id]' => 'nftDataId',
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