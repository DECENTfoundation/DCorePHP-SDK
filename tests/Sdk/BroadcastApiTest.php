<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransaction;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class BroadcastApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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

    public function testBroadcastWithCallback(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testBroadcastOperationsWithECKeyPairWithCallback(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testBroadcastOperationWithECKeyPairWithCallback(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testBroadcastOperationsWithPrivateKeyWithCallback(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testBroadcastOperationWithPrivateKeyWithCallback(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testBroadcastSynchronous(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}
