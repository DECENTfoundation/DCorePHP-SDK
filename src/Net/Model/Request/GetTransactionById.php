<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetTransactionById extends BaseRequest
{
    public function __construct(string $trxId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_transaction_by_id',
            [$trxId]
        );
    }

    /**
     * @param BaseResponse $response
     * @return ProcessedTransaction
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public static function responseToModel(BaseResponse $response)
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