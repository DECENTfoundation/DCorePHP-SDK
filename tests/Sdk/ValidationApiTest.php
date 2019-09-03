<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\Transaction;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class ValidationApiTest extends DCoreSDKTest
{

    /** @var Transaction */
    private static $trx;

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $op = new TransferOperation();
        $op
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(1));
        self::$trx = self::$sdk->getTransactionApi()->createTransactionSingleOperation($op);
    }

    /**
     * @throws Exception
     */
    public function testGetRequiredSignatures(): void
    {
        $sigs = self::$sdk->getValidationApi()->getRequiredSignatures(self::$trx, [Address::decode(DCoreSDKTest::PUBLIC_KEY_1), Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]);
        $this->assertContains('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $sigs);
    }

    /**
     * @throws Exception
     */
    public function testGetPotentialSignatures(): void
    {
        $sigs = self::$sdk->getValidationApi()->getPotentialSignatures(self::$trx);
        $this->assertContains('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $sigs);
    }

    /**
     * @throws Exception
     */
    public function testVerifyAuthorityTrue(): void
    {
        self::$trx->sign(DCoreSDKTest::PRIVATE_KEY_1);
        $this->assertTrue(self::$sdk->getValidationApi()->verifyAuthority(self::$trx));
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws Exception
     */
    public function testVerifyAccountAuthority(): void
    {
        $this->assertTrue(self::$sdk->getValidationApi()->verifyAccountAuthority(
            DCoreSDKTest::ACCOUNT_NAME_2,
            [Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]
        ));
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function testValidateTransaction(): void
    {
        self::$trx->sign(DCoreSDKTest::PRIVATE_KEY_1);
        $pt = self::$sdk->getValidationApi()->validateTransaction(self::$trx);

        $ops = $pt->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $op->getFrom()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetFeeForType(): void
    {
        $fee = self::$sdk->getValidationApi()->getFeeForType(TransferOperation::OPERATION_TYPE);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }
}
