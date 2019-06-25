<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetTransaction extends BaseRequest
{
    public function __construct(string $blockNum, string $trxInBlock)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_transaction',
            [$blockNum, $trxInBlock]
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
