<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\OperationFactory;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Response\BaseResponse;

class BroadcastTransactionWithCallback extends BaseRequest
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            'database',
            'broadcast_transaction_with_callback',
            [6, $transaction->toArray()],
            true
        );
    }

    /**
     * @param BaseResponse $response
     * @return TransactionConfirmation
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public static function responseToModel(BaseResponse $response): TransactionConfirmation
    {
        // TODO: What is this number in params response?
        [$unknownId, $rawTrxConfArray] = $response->getParams();
        $rawTrxConf = reset($rawTrxConfArray);

        $trxConf = new TransactionConfirmation();

        foreach (
            [
                '[id]' => 'id',
                '[block_num]' => 'blockNum',
                '[trx_num]' => 'trxNum',
                '[trx][ref_block_num]' => 'transaction.refBlockNum',
                '[trx][ref_block_prefix]' => 'transaction.refBlockPrefix',
                '[trx][expiration]' => 'transaction.expiration',
                // TODO: opResults
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawTrxConf, $path);
            self::getPropertyAccessor()->setValue($trxConf, $modelPath, $value);
        }

        $rawOperations = $rawTrxConf['trx']['operations'];
        $operations = [];
        foreach ($rawOperations as [$type, $rawOperation]) {
            $operation = OperationFactory::getOperation($type, $rawOperation);
            $operations[] = $operation;
        }
        $trxConf->getTransaction()->setOperations($operations);

        return $trxConf;
    }
}
