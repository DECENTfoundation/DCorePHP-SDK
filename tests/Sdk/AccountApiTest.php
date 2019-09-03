<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\DCoreApi;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\FullAccount;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Sdk\AccountApi;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class AccountApiTest extends DCoreSDKTest
{
    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testExist(): void
    {
        $this->assertTrue(self::$sdk->getAccountApi()->exist(new ChainObject('1.2.27')));
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testGet(): void
    {
        $account = self::$sdk->getAccountApi()->get(new ChainObject('1.2.27'));

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('1.2.27', $account->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetByName(): void
    {
        $account = self::$sdk->getAccountApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $account->getName());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetByNameException(): void
    {
        $this->expectException(ObjectNotFoundException::class);

        self::$sdk->getAccountApi()->getByName('non-existent');
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function testGetByNameOrId(): void
    {
        // getByName
        $accountApiMock = $this
            ->getMockBuilder(AccountApi::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByName'])
            ->getMock();
        $accountApiMock
            ->expects($this->once())
            ->method('getByName');

        /** @var DCoreApi $sdkMock */
        $sdkMock = $this
            ->getMockBuilder(DCoreApi::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sdkMock
            ->method('getAccountApi')
            ->willReturn($accountApiMock);

        $sdkMock->getAccountApi()->getByNameOrId('u961279ec8b7ae7bd62f304f7c1c3d345');

        // get
        $accountApiMock = $this
            ->getMockBuilder(AccountApi::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $accountApiMock
            ->expects($this->once())
            ->method('get');

        /** @var DCoreApi $sdkMock */
        $sdkMock = $this
            ->getMockBuilder(DCoreApi::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sdkMock
            ->method('getAccountApi')
            ->willReturn($accountApiMock);

        $sdkMock->getAccountApi()->getByNameOrId('1.2.38');
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetCountAll(): void
    {
        $count = self::$sdk->getAccountApi()->countAll();

        $this->assertInternalType('integer', $count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testFindAllReferencesByKeys(): void
    {
        $references = self::$sdk->getAccountApi()->findAllReferencesByKeys([self::PUBLIC_KEY_1]);

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testFindAllReferencesByAccount(): void
    {
        $references = self::$sdk->getAccountApi()->findAllReferencesByAccount(new ChainObject('1.2.85'));

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAll(): void
    {
        /** @var Account[] $accounts */
        $accounts = self::$sdk->getAccountApi()->getAll([new ChainObject(DCoreSDKTest::ACCOUNT_ID_1)]);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[0]->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $accounts[0]->getName());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetFullAccounts(): void
    {
        /** @var FullAccount[] $accounts */
        $accounts = self::$sdk->getAccountApi()->getFullAccounts([DCoreSDKTest::ACCOUNT_NAME_2, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1)]);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[DCoreSDKTest::ACCOUNT_ID_1]->getAccount()->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $accounts[DCoreSDKTest::ACCOUNT_ID_1]->getAccount()->getName());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_2, $accounts[DCoreSDKTest::ACCOUNT_NAME_2]->getAccount()->getName());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllByNames(): void
    {
        /** @var Account[] $accounts */
        $accounts = self::$sdk->getAccountApi()->getAllByNames([DCoreSDKTest::ACCOUNT_NAME_1, DCoreSDKTest::ACCOUNT_NAME_2]);
        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[0]->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $accounts[1]->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListAllRelative(): void
    {
        /** @var Account[] $accounts */
        $accounts = self::$sdk->getAccountApi()->listAllRelative();

        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }

        $this->assertInternalType('array', $accounts);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testFindAll(): void
    {
        $accounts = self::$sdk->getAccountApi()->findAll(DCoreSDKTest::ACCOUNT_NAME_1, '', DCoreSDKTest::ACCOUNT_ID_1, 1);

        $this->assertInternalType('array', $accounts);
        $this->assertCount(1, $accounts);

        $account = reset($accounts);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $account->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testSearchAccountHistory(): void
    {
        $transactions = self::$sdk->getAccountApi()->searchAccountHistory(new ChainObject('1.2.27'), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);

        $this->assertInternalType('array', $transactions);

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf(TransactionDetail::class, $transaction);
        }
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function testTransfer(): void
    {
        self::$sdk->getAccountApi()->transfer(
            new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)),
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(1500000),
            'hello memo here i am',
            false
        );

        /** @var TransactionDetail[] $transactions */
        $transactions = self::$sdk->getAccountApi()->searchAccountHistory(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);
        $lastTransaction = reset($transactions);
        $this->assertInstanceOf(TransactionDetail::class, $lastTransaction);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $lastTransaction->getFrom());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $lastTransaction->getTo());
        $this->assertEquals('1.3.0', $lastTransaction->getAmount()->getAssetId());
        $this->assertEquals(1500000, $lastTransaction->getAmount()->getAmount());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testCreate(): void
    {
        $accountName = 'account-test' . time();
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getAccountApi()->create($credentials, $accountName, Address::decode(DCoreSDKTest::PUBLIC_KEY_1));

        $account = self::$sdk->getAccountApi()->getByName($accountName);
        self::assertEquals($account->getName(), $accountName);
        self::assertEquals($account->getRegistrar()->getId(), DCoreSDKTest::ACCOUNT_ID_1);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $authority = (new Authority())->setKeyAuths([(new AuthMap())->setValue(DCoreSDKTest::PUBLIC_KEY_2)]);
        self::$sdk->getAccountApi()->update($credentials, null, $authority);

        $account = self::$sdk->getAccountApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1);
        /** @var AuthMap[] $keyAuths */
        $keyAuths = $account->getActive()->getKeyAuths();
        $keyAuth = reset($keyAuths);
        self::assertEquals($keyAuth->getValue()->encode(), DCoreSDKTest::PUBLIC_KEY_2);
    }
}
