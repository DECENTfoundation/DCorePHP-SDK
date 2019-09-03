<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class BroadcastApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcast(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $transaction = self::$sdk->getTransactionApi()->createTransaction([$transfer]);
        $transaction->sign($credentials->getKeyPair()->getPrivate()->toWif());

        self::$sdk->getBroadcastApi()->broadcast($transaction);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws Exception
     */
    public function testBroadcastOperationsWithECKeyPair(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        self::$sdk->getBroadcastApi()->broadcastOperationsWithECKeyPair($credentials->getKeyPair(), [$transfer]);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationWithECKeyPair(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        self::$sdk->getBroadcastApi()->broadcastOperationWithECKeyPair($credentials->getKeyPair(), $transfer);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationsWithPrivateKey(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        self::$sdk->getBroadcastApi()->broadcastOperationsWithPrivateKey($credentials->getKeyPair()->getPrivate()->toWif(), [$transfer]);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationWithPrivateKey(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        self::$sdk->getBroadcastApi()->broadcastOperationWithPrivateKey($credentials->getKeyPair()->getPrivate()->toWif(), $transfer);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function testBroadcastWithCallback(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $transaction = self::$sdk->getTransactionApi()->createTransaction([$transfer]);
        $transaction->sign($credentials->getKeyPair()->getPrivate()->toWif());

        $notice = self::$sdk->getBroadcastApi()->broadcastWithCallback($transaction);
        $ops = $notice->getTransaction()->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        self::assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $op->getTo()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationsWithECKeyPairWithCallback(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $notice = self::$sdk->getBroadcastApi()->broadcastOperationsWithECKeyPairWithCallback($credentials->getKeyPair(), [$transfer]);
        $ops = $notice->getTransaction()->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        self::assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $op->getTo()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationWithECKeyPairWithCallback(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $notice = self::$sdk->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback($credentials->getKeyPair(), $transfer);
        $ops = $notice->getTransaction()->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        self::assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $op->getTo()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationsWithPrivateKeyWithCallback(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $notice = self::$sdk->getBroadcastApi()->broadcastOperationsWithPrivateKeyWithCallback($credentials->getKeyPair()->getPrivate()->toWif(), [$transfer]);
        $ops = $notice->getTransaction()->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        self::assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $op->getTo()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testBroadcastOperationWithPrivateKeyWithCallback(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = self::$sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $notice = self::$sdk->getBroadcastApi()->broadcastOperationWithPrivateKeyWithCallback($credentials->getKeyPair()->getPrivate()->toWif(), $transfer);
        $ops = $notice->getTransaction()->getOperations();
        /** @var TransferOperation $op */
        $op = reset($ops);
        self::assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $op->getTo()->getId());
    }

    public function testBroadcastSynchronous(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}
