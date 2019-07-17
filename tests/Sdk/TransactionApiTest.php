<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetAccountHistory;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRecentTransactionById;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\GetTransactionById;
use DCorePHP\Net\Model\Request\GetTransactionHex;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class TransactionApiTest extends DCoreSDKTest
{

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testCreateTransaction(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = self::$sdk->getTransactionApi()->createTransaction([$operation]);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testCreateTransactionSingleOperation(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = self::$sdk->getTransactionApi()->createTransactionSingleOperation($operation);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllProposed(): void
    {
//        self::$sdk->getTransactionApi()->getAllProposed(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetRecent(): void
    {
        // TODO: Test response
//        $transaction = self::$sdk->getTransactionApi()->getRecent('abb2c83679c2217bd20bed723f3a9ffa8653a953');
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetById(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $transaction = self::$sdk->getTransactionApi()->getById('abb2c83679c2217bd20bed723f3a9ffa8653a953');
//        $this->assertEquals(53315, $transaction->getRefBlockNum());
//        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $transaction->getSignatures()[0]);
    }

    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetByBlockNum(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $transaction = self::$sdk->getTransactionApi()->getByBlockNum(446532, 0);
//        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $transaction->getSignatures()[0]);
//        $this->assertEquals('1.2.27', $transaction->getOperations()[0]->getFrom()->getId());
    }


    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetByConfirmation(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $transaction = self::$sdk->getTransactionApi()->getByBlockNum(446532, 0);
//        $transactionConfirmation = new TransactionConfirmation();
//        $transactionConfirmation
//            ->setId('abb2c83679c2217bd20bed723f3a9ffa8653a953')
//            ->setBlockNum('446532')
//            ->setTransaction($transaction)
//            ->setTrxNum('0');
//        $trxByConfirmation = self::$sdk->getTransactionApi()->getByConfirmation($transactionConfirmation);
//
//        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $trxByConfirmation->getSignatures()[0]);
//        $this->assertEquals(53315, $trxByConfirmation->getRefBlockNum());

    }


    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testGetHexDump(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = self::$sdk->getTransactionApi()->createTransaction([$operation]);
        $res = self::$sdk->getTransactionApi()->getHexDump($transaction);

        $this->assertEquals('00000000000000000000000000', $res);
    }
}
