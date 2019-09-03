<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AddOrUpdateContentOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class TransactionApiTest extends DCoreSDKTest
{
    /** @var OperationHistory */
    private static $op;
    /** @var ProcessedTransaction */
    private static $trx;

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        ContentApiTest::testAdd();
        $balanceChange = self::$sdk->getHistoryApi()->getOperation(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.7.0'));
        self::$op = $balanceChange->getOperation();
        self::$trx = self::$sdk->getTransactionApi()->getByBlockNum(self::$op->getBlockNum(), 0);
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     *
     * @doesNotPerformAssertions
     */
    public function testCreateTransaction(): void
    {
        $operation = new TransferOperation();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(1));
        self::$sdk->getTransactionApi()->createTransactionSingleOperation($operation);
    }

    public function testGetAllProposed(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        self::$sdk->getTransactionApi()->getAllProposed(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws Exception
     */
    public function testGetRecent(): void
    {
        $transaction = self::$sdk->getTransactionApi()->getRecent(self::$trx->getId());

        $operations = $transaction->getOperations();
        /** @var AddOrUpdateContentOperation $operation */
        $operation = reset($operations);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $operation->getAuthor());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetByBlockNum(): void
    {
        $transaction = self::$sdk->getTransactionApi()->getByBlockNum(self::$op->getBlockNum(), 0);

        $operations = $transaction->getOperations();
        /** @var AddOrUpdateContentOperation $operation */
        $operation = reset($operations);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $operation->getAuthor());
    }


    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetByConfirmation(): void
    {
        $transactionConfirmation = new TransactionConfirmation();
        $transactionConfirmation
            ->setId(self::$op->getId())
            ->setBlockNum(self::$op->getBlockNum())
            ->setTransaction(self::$trx)
            ->setTrxNum('0');
        $transaction = self::$sdk->getTransactionApi()->getByConfirmation($transactionConfirmation);

        $operations = $transaction->getOperations();
        /** @var AddOrUpdateContentOperation $operation */
        $operation = reset($operations);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $operation->getAuthor());

    }


    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGetHexDump(): void
    {
        $operation = new TransferOperation();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(1));
        $transaction = self::$sdk->getTransactionApi()->createTransactionSingleOperation($operation);
        $res = self::$sdk->getTransactionApi()->getHexDump($transaction);

        $this->assertEquals('00000000000000000000000000', $res);
    }
}
