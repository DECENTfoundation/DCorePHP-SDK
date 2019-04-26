<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class ValidateTransaction extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            'database',
            'validate_transaction',
            [$transaction->toArray()]
        );
    }

    /**
     * @param BaseResponse $response
     * @return ProcessedTransaction
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public static function responseToModel(BaseResponse $response): ProcessedTransaction
    {
        $rawTransaction = $response->getResult();
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
            $value = self::getPropertyAccessor()->getValue($rawTransaction, $path);
            self::getPropertyAccessor()->setValue($transaction, $modelPath, $value);
        }

        $rawOperations = $rawTransaction['operations'];
        $operations = [];
        foreach ($rawOperations as [$type, $rawOperation]) {
            $operation = OperationFactory::getOperation($type, $rawOperation);
            $operations[] = $operation;
        }
        $transaction->setOperations($operations);

        return $transaction;
    }
}