<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountHistory extends BaseRequest
{
    /**
     * @param ChainObject $accountId
     * @param string $startObjectId
     * @param string $endObjectId
     * @param int $limit
     */
    public function __construct(ChainObject $accountId, string $startObjectId = '0.0.0', string $endObjectId = '0.0.0', int $limit = 100)
    {
        parent::__construct(
            'database',
            'get_account_history',
            [$accountId->getId(), $startObjectId, $limit, $endObjectId]
        );
    }

    /**
     * @param BaseResponse $response
     * @return array|OperationHistory[]
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     * @throws CouldNotParseOperationTypeException
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $operationHistories = [];

        foreach ($response->getResult() as $rawOperationHistory) {
            $operationHistory = new OperationHistory();

            foreach (
                [
                    '[id]' => 'id',
                    '[result]' => 'result',
                    '[block_num]' => 'blockNum',
                    '[trx_in_block]' => 'trxInBlock',
                    '[op_in_trx]' => 'operationNumInTrx',
                    '[virtual_op]' => 'virtualOperation',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawOperationHistory, $path);
                self::getPropertyAccessor()->setValue($operationHistory, $modelPath, $value);
            }

            [$operationType, $rawOperation] = $rawOperationHistory['op'];
            if (!($operationType || $rawOperation)) {
                throw new CouldNotParseOperationTypeException($rawOperationHistory);
            }

            // @todo implement missing operations
            if ($operationType >= 46) {
                continue;
            }

            $operation = OperationFactory::getOperation($operationType, $rawOperation);

            $operationHistory->setOperation($operation);

            $operationHistories[] = $operationHistory;
        }

        return $operationHistories;
    }
}
