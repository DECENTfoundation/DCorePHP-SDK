<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetRelativeAccountHistory extends BaseRequest
{
    public function __construct(ChainObject $accountId, int $stop, int $start, int $limit)
    {
        parent::__construct(
            self::API_GROUP_HISTORY,
            'get_relative_account_history',
            [$accountId->getId(), $stop, max(0, min(100, $limit)), $start]
        );
    }

    /**
     * @param BaseResponse $response
     * @return mixed
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public static function responseToModel(BaseResponse $response)
    {
        $rawOperations = $response->getResult();
        $operations = [];

        foreach ($rawOperations as $rawOperation) {
            $operationHistory = new OperationHistory();
            foreach (
                [
                    '[id]' => 'id'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawOperation, $path);
                self::getPropertyAccessor()->setValue($operationHistory, $modelPath, $value);
            }

            $rawOperationObject = $rawOperation['op'];
            [$type, $rawOperation] = $rawOperationObject;
            $operation = OperationFactory::getOperation($type, $rawOperation);
            $operationHistory->setOperation($operation);

            $operations[] = $operationHistory;
        }

        return $operations;
    }
}