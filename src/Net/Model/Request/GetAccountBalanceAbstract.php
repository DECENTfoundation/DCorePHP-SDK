<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\OperationFactory;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetAccountBalanceAbstract extends BaseRequest
{
    /**
     * @param BaseResponse $response
     * @return BalanceChange
     */
    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult() ? self::resultToModel($response->getResult()) : null;
    }

    /**
     * @param array $result
     * @return BalanceChange
     */
    protected static function resultToModel(array $result): BalanceChange
    {
        $balanceChange = new BalanceChange();
        foreach (
            [
                '[hist_object][id]' => 'operation.id',
                '[hist_object][result]' => 'operation.result',
                '[hist_object][block_num]' => 'operation.blockNum',
                '[hist_object][trx_in_block]' => 'operation.trxInBlock',
                '[hist_object][op_in_trx]' => 'operation.operationNumInTrx',
                '[hist_object][virtual_op]' => 'operation.virtualOperation',
                '[balance][asset0][amount]' => 'balance.asset0.amount',
                '[balance][asset0][asset_id]' => 'balance.asset0.assetId',
                '[balance][asset1][amount]' => 'balance.asset1.amount',
                '[balance][asset1][asset_id]' => 'balance.asset1.assetId',
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($balanceChange, $modelPath, $value);
        }
        $rawOperationObject = $result['hist_object']['op'];
        [$type, $rawOperation] = $rawOperationObject;
        $operation = OperationFactory::getOperation($type, $rawOperation);
        $balanceChange->getOperation()->setOperation($operation);

        return $balanceChange;
    }
}