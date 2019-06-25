<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BlockData;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\GetRequiredSignatures;
use DCorePHP\Net\Model\Request\ValidateTransaction;
use DCorePHP\Net\Model\Request\VerifyAccountAuthority;
use DCorePHP\Net\Model\Request\VerifyAuthority;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class ValidationApiTest extends DCoreSDKTest
{
    /**
     * @throws \Exception
     */
    public function testGetRequiredSignatures(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $oldTrx = $this->sdk->getTransactionApi()->createTransaction([$operation]);

        $blockData = new BlockData($oldTrx->getBlockData()->getRefBlockNum(), $oldTrx->getBlockData()->getRefBlockPrefix(), $oldTrx->getBlockData()->getExpiration());
        $trx = new Transaction();
        $trx->setBlockData($blockData)->setOperations($oldTrx->getOperations());

        $sigs = $this->sdk->getValidationApi()->getRequiredSignatures($trx, [Address::decode(DCoreSDKTest::PUBLIC_KEY_1), Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]);
        $this->assertContains('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $sigs);
    }

    /**
     * @throws \Exception
     */
    public function testGetPotentialSignatures(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $oldTrx = $this->sdk->getTransactionApi()->createTransaction([$operation]);

        $blockData = new BlockData($oldTrx->getBlockData()->getRefBlockNum(), $oldTrx->getBlockData()->getRefBlockPrefix(), $oldTrx->getBlockData()->getExpiration());
        $trx = new Transaction();
        $trx->setBlockData($blockData)->setOperations($oldTrx->getOperations());

        $sigs = $this->sdk->getValidationApi()->getPotentialSignatures($trx);
        $this->assertContains('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $sigs);
    }

    /**
     * @throws \Exception
     */
    public function testVerifyAuthorityTrue(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $trx->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertTrue($this->sdk->getValidationApi()->verifyAuthority($trx));
    }

    /**
     * @throws \Exception
     */
    public function testVerifyAuthorityFalse(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $operation = new Transfer2();
//        $operation
//            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
//            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
//            ->setAmount((new AssetAmount())->setAmount(10));
//        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
//        $trx->sign(DCoreSDKTest::PRIVATE_KEY_2);
//
//        $this->assertFalse($this->sdk->getValidationApi()->verifyAuthority($trx));
    }

    public function testVerifyAccountAuthority(): void
    {
        $this->assertTrue($this->sdk->getValidationApi()->verifyAccountAuthority(DCoreSDKTest::ACCOUNT_NAME_2, [Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]));
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @throws \Exception
     */
    public function testValidateTransaction(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $trx->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->sdk->getValidationApi()->validateTransaction($trx);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFees(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));

        $fees = $this->sdk->getValidationApi()->getFees([$operation]);
        /** @var AssetAmount $fee */
        $fee = reset($fees);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFee(): void
    {
        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));

        $fee = $this->sdk->getValidationApi()->getFee($operation);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }

    public function testGetFeeByType(): void
    {
        $fee = $this->sdk->getValidationApi()->getFeeByType(Transfer2::OPERATION_TYPE);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }
}
