<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetTransactionAbstract extends BaseRequest
{
    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult() ? self::resultToModel($response->getResult()) : null;
    }

    protected static function resultToModel(array $result): ProcessedTransaction
    {
        $transaction = new ProcessedTransaction();
        foreach (
            [
                '[ref_block_num]' => 'refBlockNum',
                '[ref_block_prefix]' => 'refBlockPrefix',
                '[expiration]' => 'expiration',
                '[extensions]' => 'extensions',
                '[signatures]' => 'signatures',
                '[operation_results]' => 'opResults'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($transaction, $modelPath, $value);
        }

        $rawOperations = $result['operations'];
        $operations = [];
        foreach ($rawOperations as [$type, $rawOperation]) {
            $operation = OperationFactory::getOperation($type, $rawOperation);
            $operations[] = $operation;
        }
        $transaction->setOperations($operations);

        return $transaction;
    }
}