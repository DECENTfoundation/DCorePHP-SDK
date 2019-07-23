<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\DCoreApi;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BrainKeyInfo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ElGamalKeys;
use DCorePHP\Model\FullAccount;
use DCorePHP\Model\InvalidOperationTypeException;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Sdk\AccountApi;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class AccountApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     */
    public function testExist(): void
    {
        $this->assertTrue(self::$sdk->getAccountApi()->exist(new ChainObject('1.2.27')));
    }

    public function testGet(): void
    {
        $account = self::$sdk->getAccountApi()->get(new ChainObject('1.2.27'));

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('1.2.27', $account->getId());
    }

    public function testGetByName(): void
    {
        $account = self::$sdk->getAccountApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $account->getName());
    }

    public function testGetByNameException(): void
    {
        $this->expectException(ObjectNotFoundException::class);

        self::$sdk->getAccountApi()->getByName('non-existent');
    }

    public function testGetByNameOrId()
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

    public function testGetCountAll(): void
    {
        $count = self::$sdk->getAccountApi()->countAll();

        $this->assertInternalType('integer', $count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    /**
     * @throws ValidationException
     */
    public function testFindAllReferencesByKeys(): void
    {
        $references = self::$sdk->getAccountApi()->findAllReferencesByKeys([self::PUBLIC_KEY_1]);

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws ValidationException
     */
    public function testFindAllReferencesByAccount(): void
    {
        $references = self::$sdk->getAccountApi()->findAllReferencesByAccount(new ChainObject('1.2.85'));

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
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

    public function testListAllRelative(): void
    {
        /** @var Account[] $accounts */
        $accounts = self::$sdk->getAccountApi()->listAllRelative();

        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }

        $this->assertInternalType('array', $accounts);
    }

    public function testFindAll(): void
    {
        $accounts = self::$sdk->getAccountApi()->findAll(DCoreSDKTest::ACCOUNT_NAME_1, '', DCoreSDKTest::ACCOUNT_ID_1, 1);

        $this->assertInternalType('array', $accounts);
        $this->assertCount(1, $accounts);

        $account = reset($accounts);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $account->getId());
    }

    public function testSearchAccountHistory(): void
    {
        $transactions = self::$sdk->getAccountApi()->searchAccountHistory(new ChainObject('1.2.27'), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);

        $this->assertInternalType('array', $transactions);

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf(TransactionDetail::class, $transaction);
        }
    }

    public function testCreateCredentials(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateTransfer(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws \Exception
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

    public function testDerivePrivateKey()
    {
        $privateKey = self::$sdk->getAccountApi()->derivePrivateKey(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertInstanceOf(PrivateKey::class, $privateKey);
        $this->assertEquals('5956ee8e521a78e0cc6f6c8f65ed0a3a4be3fbe326d4d1e611fbd6454177bda4', $privateKey->toHex());
    }

    public function testSuggestBrainKey(): void
    {
        $brainKey = self::$sdk->getAccountApi()->suggestBrainKey();

        $this->assertCount(16, explode(' ', $brainKey));
    }

    public function testGenerateBrainKeyElGamalKey(): void
    {
        $brainKey = self::$sdk->getAccountApi()->generateBrainKeyElGamalKey();

        $this->assertNotEmpty($brainKey[0]);
        $this->assertNotEmpty($brainKey[1]);
        $this->assertInstanceOf(BrainKeyInfo::class, $brainKey[0]);
        $this->assertInstanceOf(ElGamalKeys::class, $brainKey[1]);
    }

    public function testGetBrainKeyInfo(): void
    {
        $brainKeyInfo = self::$sdk->getAccountApi()->getBrainKeyInfo('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING');

        $this->assertEquals('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING', $brainKeyInfo->getBrainPrivateKey());
        $this->assertEquals('5K5uAt9rPndBfEWth2fgBjDQx4gtEX9jRBB9unbo4VdVAx8jems', $brainKeyInfo->getWifPrivateKey());
        $this->assertEquals('DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw', $brainKeyInfo->getPublicKey());
    }

    public function testNormalizeBrainKey(): void
    {
        $brainKey = self::$sdk->getAccountApi()->normalizeBrainKey('failing   ahimsa inflect retour overweb podium unpiled develin bated  pudgily EXUDATE pastel isotopy osophy sellar swaying ');

        $this->assertEquals('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING', $brainKey);
    }

    public function testGenerateElGamalKeys()
    {
        $elGamalKeys = self::$sdk->getAccountApi()->generateElGamalKeys();

        $this->assertInstanceOf(ElGamalKeys::class, $elGamalKeys);
    }

    public function testRegisterAccount()
    {
        $accountName = 'ttibensky1' . date('U');

        self::$sdk->getAccountApi()->registerAccount(
            $accountName,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $account = self::$sdk->getAccountApi()->getByName($accountName);

        $this->assertEquals($accountName, $account->getName());
    }

    public function testCreateAccountWithBrainKey()
    {
        $accountName = 'ttibensky2' . date('U');

        self::$sdk->getAccountApi()->createAccountWithBrainKey(
            'FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING',
            $accountName,
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $account = self::$sdk->getAccountApi()->getByName($accountName);

        $this->assertEquals($accountName, $account->getName());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws InvalidOperationTypeException
     */
    public function testUpdateAccount(): void
    {
        $account = self::$sdk->getAccountApi()->get(new ChainObject(self::ACCOUNT_ID_1));

        $oldOptions = $account->getOptions();
        $newOptions =
            $oldOptions
                ->setAllowSubscription(false)
                ->setNumMiner(0)
                ->setVotes(['0:3'])
                ->setExtensions([])
                ->setSubscriptionPeriod(0);

        self::$sdk->getAccountApi()->updateAccount(
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            $newOptions,
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $this->expectNotToPerformAssertions();
    }
}
