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
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\GetAccountById;
use DCorePHP\Net\Model\Request\GetAccountByName;
use DCorePHP\Net\Model\Request\GetAccountCount;
use DCorePHP\Net\Model\Request\GetAccountReferences;
use DCorePHP\Net\Model\Request\GetAccountsById;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetFullAccounts;
use DCorePHP\Net\Model\Request\GetKeyReferences;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\ListAccounts;
use DCorePHP\Net\Model\Request\LookupAccountNames;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Net\Model\Request\SearchAccounts;
use DCorePHP\Net\Model\Response\BaseResponse;
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_accounts",[["1.2.34"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountById::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
                ));
        }

        $exists = $this->sdk->getAccountApi()->exist(new ChainObject('1.2.34'));
        $this->assertTrue($exists);
    }

    public function testGet(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_accounts",[["1.2.34"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountById::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
                ));
        }

        $account = $this->sdk->getAccountApi()->get(new ChainObject('1.2.34'));

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('1.2.34', $account->getId());
    }

    public function testGetByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_account_by_name",["public-account-9"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountByName::responseToModel(new BaseResponse('{"id":1,"result":{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}}'))
                ));
        }

        $account = $this->sdk->getAccountApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $account->getName());
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_account_count",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountCount::responseToModel(new BaseResponse('{"id":1,"result":12588}'))
                ));
        }

        $count = $this->sdk->getAccountApi()->countAll();

        $this->assertInternalType('integer', $count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    /**
     * @throws ValidationException
     */
    public function testFindAllReferencesByKeys(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_key_references",[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetKeyReferences::responseToModel(new BaseResponse('{"id":1,"result":[["1.2.27","1.2.51","1.2.52","1.2.53","1.2.132","1.2.133","1.2.135","1.2.137","1.2.139","1.2.141","1.2.143","1.2.145","1.2.147","1.2.148","1.2.149","1.2.150","1.2.151","1.2.153"]]}'))
                ));
        }

        $references = $this->sdk->getAccountApi()->findAllReferencesByKeys([self::PUBLIC_KEY_1]);

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws ValidationException
     */
    public function testFindAllReferencesByAccount(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_account_references",["1.2.85"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountReferences::responseToModel(new BaseResponse('{"id":1,"result":["1.2.0"]}'))
                ));
        }

        $references = $this->sdk->getAccountApi()->findAllReferencesByAccount(new ChainObject('1.2.85'));

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws ValidationException
     */
    public function testGetAll(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_objects",[["1.2.27"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountsById::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}'))
                ));
        }

        /** @var Account[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getAll([new ChainObject(DCoreSDKTest::ACCOUNT_ID_1)]);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[0]->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $accounts[0]->getName());
    }

    /**
     * @throws ValidationException
     */
    public function testGetFullAccounts(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_full_accounts",[["public-account-10","1.2.27"],false]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetFullAccounts::responseToModel(new BaseResponse('{"id":1,"result":[["1.2.27",{"account":{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0},"statistics":{"id":"2.5.27","owner":"1.2.27","most_recent_op":"2.8.1836364","total_ops":1336,"total_core_in_orders":0,"pending_fees":0,"pending_vested_fees":0},"registrar_name":"temp-account","votes":[{"id":"1.4.4","miner_account":"1.2.7","last_aslot":984345,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.4","vote_id":"0:3","total_votes":"1999062898072","url":"","total_missed":36110,"last_confirmed_block_num":587126,"vote_ranking":0}],"balances":[{"id":"2.4.13","owner":"1.2.27","asset_type":"1.3.0","balance":"995275624334"},{"id":"2.4.73","owner":"1.2.27","asset_type":"1.3.36","balance":990}],"vesting_balances":[],"proposals":[]}],["public-account-10",{"account":{"id":"1.2.28","registrar":"1.2.2","name":"public-account-10","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp",1]]},"options":{"memo_key":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.28","top_n_control_flags":0},"statistics":{"id":"2.5.28","owner":"1.2.28","most_recent_op":"2.8.1836359","total_ops":950,"total_core_in_orders":0,"pending_fees":0,"pending_vested_fees":0},"registrar_name":"temp-account","votes":[{"id":"1.4.4","miner_account":"1.2.7","last_aslot":984345,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.4","vote_id":"0:3","total_votes":"1999062898072","url":"","total_missed":36110,"last_confirmed_block_num":587126,"vote_ranking":0}],"balances":[{"id":"2.4.14","owner":"1.2.28","asset_type":"1.3.0","balance":"1003781973736"}],"vesting_balances":[],"proposals":[]}]]}'))
                ));
        }

        /** @var FullAccount[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getFullAccounts([DCoreSDKTest::ACCOUNT_NAME_2, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1)]);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[DCoreSDKTest::ACCOUNT_ID_1]->getAccount()->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_1, $accounts[DCoreSDKTest::ACCOUNT_ID_1]->getAccount()->getName());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_NAME_2, $accounts[DCoreSDKTest::ACCOUNT_NAME_2]->getAccount()->getName());
    }

    public function testGetAllByNames(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_account_names",[["public-account-9","public-account-10"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAccountNames::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0},{"id":"1.2.28","registrar":"1.2.2","name":"public-account-10","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp",1]]},"options":{"memo_key":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.28","top_n_control_flags":0}]}'))
                ));
        }

        /** @var Account[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getAllByNames([DCoreSDKTest::ACCOUNT_NAME_1, DCoreSDKTest::ACCOUNT_NAME_2]);
        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $accounts[0]->getId());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $accounts[1]->getId());
    }

    public function testListAllRelative(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_accounts",["",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    ListAccounts::responseToModel(new BaseResponse('{"id":1,"result":[["aaaaa.aaaaa","1.2.144"],["aaaaaaaaa","1.2.426"],["aaaaaabbbbbbb","1.2.128"],["aaaadsdsada","1.2.132"],["abcde","1.2.145"],["abcdef","1.2.175"],["abcdeh","1.2.176"],["acc-1","1.2.343"],["acc-10","1.2.352"],["acc-2","1.2.344"],["acc-3","1.2.345"],["acc-4","1.2.346"],["acc-5","1.2.347"],["acc-6","1.2.348"],["acc-7","1.2.349"],["acc-8","1.2.350"],["acc-9","1.2.351"],["addsadadasa","1.2.129"],["alaxio","1.2.55"],["all-txs","1.2.85"],["all-txs2","1.2.86"],["alx-customer-00d3afac-cb38-4eb6-955a-017e53630b21","1.2.584"],["alx-customer-01265aeb-2bdb-4caa-975e-83ced23365ae","1.2.249"],["alx-customer-02b2e883-9c41-4d72-beb0-b9275b5c5be9","1.2.476"],["alx-customer-02fb0082-719d-43ae-8f24-2e2302ff5f9b","1.2.11623"],["alx-customer-030bed20-c5d4-43d9-a2dc-b1d9366595c9","1.2.1123"],["alx-customer-03320597-b5be-4f30-b8e1-b3a001d72b50","1.2.223"],["alx-customer-03f858e4-f23b-4a64-acdf-f9abb96fee54","1.2.741"],["alx-customer-04a02a5b-5629-4d0b-a46e-84751b49747f","1.2.216"],["alx-customer-05479a4e-cc06-4fba-a7b1-8399a05576c3","1.2.976"],["alx-customer-05d2464b-8401-493e-8db1-25996bd2b49a","1.2.12119"],["alx-customer-05e81402-f4d4-47a3-8f11-9af15d3a2884","1.2.11804"],["alx-customer-076a1412-5745-4761-b19c-b8a736726f65","1.2.11703"],["alx-customer-08431fe7-7212-4a8e-a3c9-af4205492dbc","1.2.11887"],["alx-customer-0859fa21-7a22-4f77-956a-a330d508a820","1.2.11680"],["alx-customer-08bfd381-c250-49e5-8dc7-25c13ecac285","1.2.11934"],["alx-customer-0c7d8abc-1432-4041-9da8-0b0d859fa0cd","1.2.228"],["alx-customer-0cd159b4-bcbf-4564-ab45-6810d50a25cf","1.2.457"],["alx-customer-0d3c59dd-9c03-4df3-b4f1-1435c719d4f9","1.2.11635"],["alx-customer-0db55454-9fce-45d0-a8b8-309cb0f5d0d8","1.2.11964"],["alx-customer-0dbe344e-fba2-4697-a609-7bcddf7bc8a1","1.2.1121"],["alx-customer-0e1d51d8-64bc-461b-8f09-7a62ba498a2b","1.2.245"],["alx-customer-0ea1a063-ba89-450c-8ed7-c339388cd84c","1.2.685"],["alx-customer-0f2925c6-546a-4f64-abfd-37a994d28cfc","1.2.759"],["alx-customer-0f99b138-ec36-4192-a197-f87f41c76707","1.2.11896"],["alx-customer-10f4fca4-26f2-4849-9759-c55c73a35a97","1.2.651"],["alx-customer-11040c0a-6e0b-4009-8700-fb4e4e04b282","1.2.11713"],["alx-customer-1112c494-01d6-4c48-8509-7877939d5a1e","1.2.11597"],["alx-customer-123234db-869f-4f84-9b04-0a0b0889b812","1.2.11698"],["alx-customer-12b448c1-795b-467a-a89e-00504e140fbc","1.2.492"],["alx-customer-132246bb-ea85-4915-a676-97b1affdf09d","1.2.493"],["alx-customer-135ede0e-21b5-41ba-b667-c42b7f9cbbc1","1.2.1120"],["alx-customer-13b6054a-a8f5-48c7-998e-fa72fe0430c5","1.2.930"],["alx-customer-14703ff8-a0fb-49ab-942d-3e6d907b3a1c","1.2.220"],["alx-customer-14a9dc68-0ac1-417f-9dec-934bbf71c015","1.2.975"],["alx-customer-159add8a-95fe-4c25-89b9-18ebe1de7705","1.2.221"],["alx-customer-15a73167-a519-41c4-a852-23b8943617c7","1.2.233"],["alx-customer-16f6502e-1911-43b9-a7e4-fa6411fd063f","1.2.989"],["alx-customer-17800e97-5775-411e-9ec1-50822685b442","1.2.235"],["alx-customer-17f47e71-47e0-48e3-882a-1b6d72ccf4fa","1.2.196"],["alx-customer-187376ec-0686-4f41-9535-07ca9a81a4b2","1.2.260"],["alx-customer-188f023f-54b8-41c5-8464-964802af3aa1","1.2.11737"],["alx-customer-18e92505-8fab-45f3-a675-0f5e082bd6be","1.2.1125"],["alx-customer-18er2545-8fab-45f3-a675-0f5e082bd6be","1.2.1126"],["alx-customer-1b89144e-4f9a-4314-b40d-ec009216bd3a","1.2.11591"],["alx-customer-1d4d131d-d617-4812-a16d-73fa8b33cc3c","1.2.434"],["alx-customer-207067d4-2f2c-4275-866c-8bf5e2df6fff","1.2.11816"],["alx-customer-2178ed94-8ca5-4353-9139-97e18bf43d94","1.2.11702"],["alx-customer-21804c3a-f61a-44c4-b54b-1c2037796657","1.2.450"],["alx-customer-22de5454-373c-4436-bf7c-fd2f99b96478","1.2.12265"],["alx-customer-2399a7d6-b1bb-45ff-ad31-dec5103624a1","1.2.751"],["alx-customer-2506bd86-284a-40f6-9e17-592692a6f0ad","1.2.11644"],["alx-customer-251cb6cb-b999-4947-a672-01b3866a21ea","1.2.649"],["alx-customer-25207aeb-2abb-4b4b-9418-362a36617a37","1.2.191"],["alx-customer-25fc19ec-914b-458c-ba6a-d3aa7fd69529","1.2.258"],["alx-customer-265c0bfc-4fa8-4625-afc2-6fc88381671c","1.2.11604"],["alx-customer-269589e8-0906-476e-9226-2ea958a6e788","1.2.11593"],["alx-customer-26c32df5-4007-413c-b1ee-ee5e3ca46d97","1.2.11730"],["alx-customer-2731838d-2653-4507-ac89-0af0e4281e5d","1.2.448"],["alx-customer-276a0336-b783-4ed1-8aab-38df9f79c62d","1.2.11708"],["alx-customer-277a881c-d01a-48ad-9aa4-781f1f811a34","1.2.11789"],["alx-customer-290490a5-3320-4153-a953-2e9e6d48929d","1.2.589"],["alx-customer-29bdcb57-31e6-4bba-9f07-4951ada6f41d","1.2.11681"],["alx-customer-2ad44322-693b-4271-a222-622fb0c64666","1.2.248"],["alx-customer-2c2bae92-d6a0-4029-84b4-94f70582a09d","1.2.489"],["alx-customer-2c3963bd-69f6-4bc5-9d2e-2da88bb52342","1.2.11880"],["alx-customer-2d0211da-4c6d-4d46-9405-1fb9b56d9650","1.2.12063"],["alx-customer-2ddac1bf-6be2-40b9-bf78-f3d8516b2510","1.2.12152"],["alx-customer-2e425be5-ac58-4719-9055-e75fb2eb44c4","1.2.619"],["alx-customer-2e507fe3-4bab-46bc-be0b-0574034f9f12","1.2.744"],["alx-customer-2f3a0630-118c-4774-a146-a7aff7d9066e","1.2.256"],["alx-customer-30067c65-7a6a-47f5-8142-fbe59cee23f8","1.2.270"],["alx-customer-30de0a13-099b-4829-b717-8337dd340147","1.2.247"],["alx-customer-30e2dc86-57c8-46ce-a90a-e482096c867e","1.2.778"],["alx-customer-3116d24a-8a16-4ddb-8ddf-f64d8d166f07","1.2.461"],["alx-customer-32025581-4da4-4cdb-8a6e-8c321245f793","1.2.644"],["alx-customer-339dd198-ac4b-427d-be73-d9e6904b550b","1.2.441"],["alx-customer-34916e6d-4a26-40c2-957b-7caf6db48096","1.2.11658"],["alx-customer-34cec48f-f240-452d-9314-805ea39544d6","1.2.985"],["alx-customer-352d2f4c-d883-4752-bebb-2044a620f13e","1.2.406"]]}'))
                ));
        }

        /** @var Account[] $accounts */
        $accounts = $this->sdk->getAccountApi()->listAllRelative();

        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }

        $this->assertInternalType('array', $accounts);
    }

    public function testFindAll(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"search_accounts",["public-account-9","","1.2.27",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    SearchAccounts::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}'))
                ));
        }

        $accounts = $this->sdk->getAccountApi()->findAll(DCoreSDKTest::ACCOUNT_NAME_1, '', DCoreSDKTest::ACCOUNT_ID_1, 1);

        $this->assertInternalType('array', $accounts);
        $this->assertCount(1, $accounts);

        $account = reset($accounts);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $account->getId());
    }

    public function testSearchAccountHistory(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"search_account_history",["1.2.34","-time","0.0.0",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    SearchAccountHistory::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"2.17.54766","m_from_account":"1.2.34","m_to_account":"1.2.35","m_operation_type":0,"m_transaction_amount":{"amount":1500000,"asset_id":"1.3.0"},"m_transaction_fee":{"amount":500000,"asset_id":"1.3.0"},"m_str_description":"transfer","m_transaction_encrypted_memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"15500506128071447","message":"e912883dce55f7a60b29dda405531011fcd0583da5eade7c445d2b97c79afdde0cf8ed811ea6422ea8416cf852e461a28062d884f163b5264ec68e838819624d"},"m_timestamp":"2019-02-13T09:36:50"}]}'))
                ));
        }

        $transactions = $this->sdk->getAccountApi()->searchAccountHistory(new ChainObject('1.2.34'), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);

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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":1500000,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000068656c6c6f206d656d6f2068657265206920616d","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":1500000,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000068656c6c6f206d656d6f2068657265206920616d","nonce":"0"}}]],"ref_block_num":7333,"ref_block_prefix":"684177972","expiration":"2019-05-16T14:28:40","signatures":["1f171476c73164792d246bb0233a416dc049b14d44a2cc6c11342d1005598080564da2973465a458b79f66e174686eb9418c96bbeb5e4cedbda81d724f7c49b49a"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"search_account_history",["1.2.27","-time","0.0.0",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1055909,"head_block_id":"00101ca534bac72851a0cb1fa5b4fcc419914425","time":"2019-05-16T14:28:10","current_miner":"1.4.9","next_maintenance_time":"2019-05-17T00:00:00","last_budget_time":"2019-05-16T00:00:00","unspent_fee_budget":20035147,"mined_rewards":"350501000000","miner_budget_from_fees":44333392,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":19,"recently_missed_count":2,"current_aslot":1469669,"recent_slots_filled":"334965409302299819321300764794325825531","dynamic_flags":0,"last_irreversible_block_num":1055909}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":1,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"bc75d8bbe3bde3cb2c1530e7306d9058ada017df","block_num":1055911,"trx_num":0,"trx":{"ref_block_num":7333,"ref_block_prefix":684177972,"expiration":"2019-05-16T14:28:40","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":1500000,"asset_id":"1.3.0"},"memo":{"from":"DCT1111111111111111111111111111111114T1Anm","to":"DCT1111111111111111111111111111111114T1Anm","nonce":0,"message":"0000000068656c6c6f206d656d6f2068657265206920616d"},"extensions":[]}]],"extensions":[],"signatures":["1f171476c73164792d246bb0233a416dc049b14d44a2cc6c11342d1005598080564da2973465a458b79f66e174686eb9418c96bbeb5e4cedbda81d724f7c49b49a"],"operation_results":[[0,{}]]}}]]}')),
                    SearchAccountHistory::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"2.17.916335","m_from_account":"1.2.27","m_to_account":"1.2.28","m_operation_type":0,"m_transaction_amount":{"amount":1500000,"asset_id":"1.3.0"},"m_transaction_fee":{"amount":100000,"asset_id":"1.3.0"},"m_str_description":"transfer","m_transaction_encrypted_memo":{"from":"DCT1111111111111111111111111111111114T1Anm","to":"DCT1111111111111111111111111111111114T1Anm","nonce":0,"message":"0000000068656c6c6f206d656d6f2068657265206920616d"},"m_timestamp":"2019-04-18T12:22:15"}]}'))
                ));
        }

        $this->sdk->getAccountApi()->transfer(
            new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)),
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(1500000),
            'hello memo here i am',
            false
        );

        /** @var TransactionDetail[] $transactions */
        $transactions = $this->sdk->getAccountApi()->searchAccountHistory(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);
        $lastTransaction = reset($transactions);
        $this->assertInstanceOf(TransactionDetail::class, $lastTransaction);
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $lastTransaction->getFrom());
        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $lastTransaction->getTo());
        $this->assertEquals('1.3.0', $lastTransaction->getAmount()->getAssetId());
        $this->assertEquals(1500000, $lastTransaction->getAmount()->getAmount());
    }

    public function testDerivePrivateKey()
    {
        $privateKey = $this->sdk->getAccountApi()->derivePrivateKey(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertInstanceOf(PrivateKey::class, $privateKey);
        $this->assertEquals('b3011460ed115b70996d769dcbf8db173d9f113649ac4d16df57bdb66514a595', $privateKey->toHex());
    }

    public function testSuggestBrainKey(): void
    {
        $brainKey = $this->sdk->getAccountApi()->suggestBrainKey();

        $this->assertCount(16, explode(' ', $brainKey));
    }

    public function testGenerateBrainKeyElGamalKey(): void
    {
        $brainKey = $this->sdk->getAccountApi()->generateBrainKeyElGamalKey();

        $this->assertNotEmpty($brainKey[0]);
        $this->assertNotEmpty($brainKey[1]);
        $this->assertInstanceOf(BrainKeyInfo::class, $brainKey[0]);
        $this->assertInstanceOf(ElGamalKeys::class, $brainKey[1]);
    }

    public function testGetBrainKeyInfo(): void
    {
        $brainKeyInfo = $this->sdk->getAccountApi()->getBrainKeyInfo('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING');

        $this->assertEquals('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING', $brainKeyInfo->getBrainPrivateKey());
        $this->assertEquals('5K5uAt9rPndBfEWth2fgBjDQx4gtEX9jRBB9unbo4VdVAx8jems', $brainKeyInfo->getWifPrivateKey());
        $this->assertEquals('DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw', $brainKeyInfo->getPublicKey());
    }

    public function testNormalizeBrainKey(): void
    {
        $brainKey = $this->sdk->getAccountApi()->normalizeBrainKey('failing   ahimsa inflect retour overweb podium unpiled develin bated  pudgily EXUDATE pastel isotopy osophy sellar swaying ');

        $this->assertEquals('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING', $brainKey);
    }

    public function testGenerateElGamalKeys()
    {
        $elGamalKeys = $this->sdk->getAccountApi()->generateElGamalKeys();

        $this->assertInstanceOf(ElGamalKeys::class, $elGamalKeys);
    }

    public function testRegisterAccount()
    {
        $accountName = 'ttibensky1' . date('U');

        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[1,{"fee":{"amount":0,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"'.$req->getParams()[0][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[1,{"fee":{"amount":100000,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"'.$req->getParams()[1]['operations'][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"ref_block_num":7807,"ref_block_prefix":"147619929","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_account_by_name",["'.$req->getParams()[0].'"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1056383,"head_block_id":"00101e7f5980cc08f0cc1cc46a0329d34d88dff7","time":"2019-05-16T15:11:45","current_miner":"1.4.6","next_maintenance_time":"2019-05-17T00:00:00","last_budget_time":"2019-05-16T00:00:00","unspent_fee_budget":18819337,"mined_rewards":"368039000000","miner_budget_from_fees":44333392,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":20,"recently_missed_count":3,"current_aslot":1470192,"recent_slots_filled":"337603140471808002650903553372279668733","dynamic_flags":0,"last_irreversible_block_num":1056383}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"ee4bf12f11648c58acca98a9d279d2917d906464","block_num":1056384,"trx_num":0,"trx":{"ref_block_num":7807,"ref_block_prefix":147619929,"expiration":"2019-05-16T15:12:18","operations":[[1,{"fee":{"amount":100000,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"ttibensky11558019492","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":{}}]],"extensions":[],"signatures":["202ffdb680159dfc83e8f51451c3067e14db72ad3717a346875e6014548f98cc051862ff401f1a8514f28500b8cf30344e127291402577ca2b8ab69df31bb88ada"],"operation_results":[[1,"1.2.502"]]}}]]}')),
                    GetAccountByName::responseToModel(new BaseResponse('{"id":5,"result":{"id":"1.2.157","registrar":"1.2.27","name":"'.$accountName.'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.157","top_n_control_flags":0}}'))
                ));
        }

        $this->sdk->getAccountApi()->registerAccount(
            $accountName,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $account = $this->sdk->getAccountApi()->getByName($accountName);

        $this->assertEquals($accountName, $account->getName());
    }

    public function testCreateAccountWithBrainKey()
    {
        $accountName = 'ttibensky2' . date('U');

        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[1,{"fee":{"amount":0,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"'.$req->getParams()[0][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[1,{"fee":{"amount":100000,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"'.$req->getParams()[1]['operations'][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"ref_block_num":7935,"ref_block_prefix":"1757609034","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_account_by_name",["'.$req->getParams()[0].'"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1056511,"head_block_id":"00101eff4afcc268bc8d60d8f07298dcb2d4ddfe","time":"2019-05-16T15:23:20","current_miner":"1.4.1","next_maintenance_time":"2019-05-17T00:00:00","last_budget_time":"2019-05-16T00:00:00","unspent_fee_budget":18491017,"mined_rewards":"372775000000","miner_budget_from_fees":44333392,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":21,"recently_missed_count":0,"current_aslot":1470331,"recent_slots_filled":"338953133849580042525553292597144321791","dynamic_flags":0,"last_irreversible_block_num":1056511}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"332868612b737e8d64110d008f97d829f49278c6","block_num":1056512,"trx_num":0,"trx":{"ref_block_num":7935,"ref_block_prefix":1757609034,"expiration":"2019-05-16T15:23:51","operations":[[1,{"fee":{"amount":100000,"asset_id":"1.3.0"},"registrar":"1.2.27","name":"ttibensky21558020192","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":{}}]],"extensions":[],"signatures":["1f45abe99809dcc0d3baf8d0f5c67003ae644051cb039911a1db3aa9ad03299e7f0181ff9d63ae0475d52a20ecd7886def39b3c53458ee460fc02b541fd790abff"],"operation_results":[[1,"1.2.503"]]}}]]}')),
                    GetAccountByName::responseToModel(new BaseResponse('{"id":5,"result":{"id":"1.2.158","registrar":"1.2.27","name":"'.$accountName.'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.158","top_n_control_flags":0}}'))
                ));
        }

        $this->sdk->getAccountApi()->createAccountWithBrainKey(
            'FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING',
            $accountName,
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $account = $this->sdk->getAccountApi()->getByName($accountName);

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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(6))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_accounts",[["1.2.27"]]]}'; })],
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_accounts",[["1.2.27"]]]}'; })],
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_required_fees",[[[2,{"fee":{"amount":0,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1}}]],"1.3.0"]]}'; })],
                    [$this->callback(static function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[2,{"fee":{"amount":100000,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1}}]],"ref_block_num":8015,"ref_block_prefix":"3308055236","expiration":"2019-05-16T15:31:14","signatures":["1f6ca6b70597b9e05053c9cafbd35924a8a4ba0568489c5400671793b82f0394017fb1f0c6fc89187023621795a93ddc5ba7e5faeac5d849992842ee6b71d10709"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountById::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":1056591,"head_block_id":"00101f4fc4ea2cc57c3072169597604c572f0155","time":"2019-05-16T15:30:40","current_miner":"1.4.2","next_maintenance_time":"2019-05-17T00:00:00","last_budget_time":"2019-05-16T00:00:00","unspent_fee_budget":18285817,"mined_rewards":"375735000000","miner_budget_from_fees":44333392,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":22,"recently_missed_count":0,"current_aslot":1470419,"recent_slots_filled":"339949897583409833735953563494228426239","dynamic_flags":0,"last_irreversible_block_num":1056591}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":4,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":5,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"00ec93118167ef4ce35a49b707e3cdd35751d047","block_num":1056592,"trx_num":0,"trx":{"ref_block_num":8015,"ref_block_prefix":3308055236,"expiration":"2019-05-16T15:31:14","operations":[[2,{"fee":{"amount":100000,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1},"extensions":{}}]],"extensions":[],"signatures":["1f6ca6b70597b9e05053c9cafbd35924a8a4ba0568489c5400671793b82f0394017fb1f0c6fc89187023621795a93ddc5ba7e5faeac5d849992842ee6b71d10709"],"operation_results":[[0,{}]]}}]]}'))
                ));
        }

        $account = $this->sdk->getAccountApi()->get(new ChainObject(self::ACCOUNT_ID_1));

        $oldOptions = $account->getOptions();
        $newOptions =
            $oldOptions
                ->setAllowSubscription(false)
                ->setNumMiner(0)
                ->setVotes(['0:3'])
                ->setExtensions([])
                ->setSubscriptionPeriod(0);

        $this->sdk->getAccountApi()->updateAccount(
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            $newOptions,
            DCoreSDKTest::PRIVATE_KEY_1
        );

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }
}
