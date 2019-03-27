<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\DCoreApi;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BrainKeyInfo;
use DCorePHP\Model\ElGamalKeys;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\FullAccount;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountById;
use DCorePHP\Net\Model\Request\GetAccountByName;
use DCorePHP\Net\Model\Request\GetAccountCount;
use DCorePHP\Net\Model\Request\GetAccountHistory;
use DCorePHP\Net\Model\Request\GetAccountReferences;
use DCorePHP\Net\Model\Request\GetAccountsById;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetFullAccounts;
use DCorePHP\Net\Model\Request\GetKeyReferences;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\History;
use DCorePHP\Net\Model\Request\GetAccountBalances;
use DCorePHP\Net\Model\Request\ListAccounts;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\LookupAccountNames;
use DCorePHP\Net\Model\Request\NetworkBroadcast;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Net\Model\Request\SearchAccounts;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHP\Sdk\AccountApi;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;

class AccountApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testExist(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_accounts",[["1.2.34"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
                ));
        }

        $exists = $this->sdk->getAccountApi()->exist(new ChainObject('1.2.34'));
        $this->assertTrue($exists);
    }

    public function testGet(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_accounts",[["1.2.34"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
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
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_by_name",["u961279ec8b7ae7bd62f304f7c1c3d345"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountByName::responseToModel(new BaseResponse('{"id":3,"result":{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}}'))
                ));
        }

        $account = $this->sdk->getAccountApi()->getByName('u961279ec8b7ae7bd62f304f7c1c3d345');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('u961279ec8b7ae7bd62f304f7c1c3d345', $account->getName());
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
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_count",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountCount::responseToModel(new BaseResponse('{"id":3,"result":12588}'))
                ));
        }

        $count = $this->sdk->getAccountApi()->countAll();

        $this->assertInternalType('integer', $count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindAllReferencesByKeys(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_key_references",[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetKeyReferences::responseToModel(new BaseResponse('{"id":3,"result":[["1.2.34","1.2.775","1.2.12319","1.2.12342","1.2.12343","1.2.12344","1.2.12350","1.2.12354","1.2.12355","1.2.12357","1.2.12359","1.2.12361","1.2.12363","1.2.12365","1.2.12367","1.2.12370","1.2.12376","1.2.12378","1.2.12379","1.2.12380","1.2.12381","1.2.12382","1.2.12384","1.2.12386","1.2.12388","1.2.12390","1.2.12393","1.2.12395","1.2.12397","1.2.12398","1.2.12400","1.2.12401","1.2.12403","1.2.12404","1.2.12405","1.2.12406","1.2.12408","1.2.12410","1.2.12412","1.2.12414","1.2.12416","1.2.12418","1.2.12420","1.2.12422","1.2.12424","1.2.12426","1.2.12428","1.2.12430","1.2.12432","1.2.12434","1.2.12436","1.2.12438","1.2.12440","1.2.12443","1.2.12445","1.2.12448","1.2.12450","1.2.12451","1.2.12453","1.2.12454","1.2.12455","1.2.12457","1.2.12459","1.2.12461","1.2.12463","1.2.12466","1.2.12468","1.2.12470","1.2.12472","1.2.12474","1.2.12476","1.2.12478","1.2.12480","1.2.12483","1.2.12485","1.2.12498","1.2.12508","1.2.12509","1.2.12511","1.2.12513","1.2.12516","1.2.12518","1.2.12520","1.2.12522","1.2.12526","1.2.12529","1.2.12531","1.2.12533","1.2.12535","1.2.12537","1.2.12539","1.2.12541","1.2.12543","1.2.12545","1.2.12547","1.2.12550","1.2.12552","1.2.12554","1.2.12556","1.2.12558","1.2.12560","1.2.12563","1.2.12565","1.2.12567","1.2.12569","1.2.12574","1.2.12576","1.2.12578","1.2.12580","1.2.12582","1.2.12583","1.2.12584","1.2.12587","1.2.12599","1.2.12601","1.2.12626","1.2.12628","1.2.12631","1.2.12633","1.2.12635","1.2.12637","1.2.12643","1.2.12646","1.2.12659","1.2.12661","1.2.12663","1.2.12665","1.2.12670","1.2.12671","1.2.12672"]]}'))
                ));
        }

        $references = $this->sdk->getAccountApi()->findAllReferencesByKeys([self::PUBLIC_KEY_1]);

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testFindAllReferencesByAccount(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_references",["1.2.85"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountReferences::responseToModel(new BaseResponse('{"id":3,"result":["1.2.0"]}'))
                ));
        }

        $references = $this->sdk->getAccountApi()->findAllReferencesByAccount(new ChainObject('1.2.85'));

        $this->assertContainsOnlyInstancesOf(ChainObject::class, $references);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAll(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_objects",[["1.2.34"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountsById::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
                ));
        }

        /** @var Account[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getAll([new ChainObject('1.2.34')]);
        $this->assertEquals('1.2.34', $accounts[0]->getId());
        $this->assertEquals('u961279ec8b7ae7bd62f304f7c1c3d345', $accounts[0]->getName());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFullAccounts(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_full_accounts",[["u961279ec8b7ae7bd62f304f7c1c3d345","1.2.34"],false]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetFullAccounts::responseToModel(new BaseResponse('{"id":3,"result":[["1.2.34",{"account":{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0},"statistics":{"id":"2.5.34","owner":"1.2.34","most_recent_op":"2.8.115606","total_ops":2165,"total_core_in_orders":0,"pending_fees":0,"pending_vested_fees":0},"registrar_name":"decent","votes":[{"id":"1.4.6","miner_account":"1.2.9","last_aslot":10590863,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.3","vote_id":"0:5","total_votes":"25243546611","url":"","total_missed":478966,"last_confirmed_block_num":4516262,"vote_ranking":10},{"id":"1.4.9","miner_account":"1.2.12","last_aslot":10590873,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.8","vote_id":"0:8","total_votes":"727726339739","url":"","total_missed":477819,"last_confirmed_block_num":4516270,"vote_ranking":5}],"balances":[{"id":"2.4.29","owner":"1.2.34","asset_type":"1.3.0","balance":"18744844406"},{"id":"2.4.7564","owner":"1.2.34","asset_type":"1.3.44","balance":101},{"id":"2.4.7563","owner":"1.2.34","asset_type":"1.3.53","balance":979600000},{"id":"2.4.238","owner":"1.2.34","asset_type":"1.3.54","balance":"4764856000"}],"vesting_balances":[],"proposals":[]}],["u961279ec8b7ae7bd62f304f7c1c3d345",{"account":{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0},"statistics":{"id":"2.5.34","owner":"1.2.34","most_recent_op":"2.8.115606","total_ops":2165,"total_core_in_orders":0,"pending_fees":0,"pending_vested_fees":0},"registrar_name":"decent","votes":[{"id":"1.4.6","miner_account":"1.2.9","last_aslot":10590863,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.3","vote_id":"0:5","total_votes":"25243546611","url":"","total_missed":478966,"last_confirmed_block_num":4516262,"vote_ranking":10},{"id":"1.4.9","miner_account":"1.2.12","last_aslot":10590873,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.8","vote_id":"0:8","total_votes":"727726339739","url":"","total_missed":477819,"last_confirmed_block_num":4516270,"vote_ranking":5}],"balances":[{"id":"2.4.29","owner":"1.2.34","asset_type":"1.3.0","balance":"18744844406"},{"id":"2.4.7564","owner":"1.2.34","asset_type":"1.3.44","balance":101},{"id":"2.4.7563","owner":"1.2.34","asset_type":"1.3.53","balance":979600000},{"id":"2.4.238","owner":"1.2.34","asset_type":"1.3.54","balance":"4764856000"}],"vesting_balances":[],"proposals":[]}]]}'))
                ));
        }

        /** @var FullAccount[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getFullAccounts(['u961279ec8b7ae7bd62f304f7c1c3d345', new ChainObject('1.2.34')]);
        $this->assertEquals('1.2.15', $accounts['1.2.34']->getAccount()->getRegistrar());
        $this->assertEquals('u961279ec8b7ae7bd62f304f7c1c3d345', $accounts['1.2.34']->getAccount()->getName());
        $this->assertEquals('decent', $accounts['u961279ec8b7ae7bd62f304f7c1c3d345']->getRegistrarName());
    }

    public function testGetAllByNames(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_account_names",[["u961279ec8b7ae7bd62f304f7c1c3d345","u3a7b78084e7d3956442d5a4d439dad51"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    LookupAccountNames::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0},{"id":"1.2.35","registrar":"1.2.15","name":"u3a7b78084e7d3956442d5a4d439dad51","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP",1]]},"options":{"memo_key":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.35","top_n_control_flags":0}]}'))
                ));
        }

        /** @var Account[] $accounts */
        $accounts = $this->sdk->getAccountApi()->getAllByNames(['u961279ec8b7ae7bd62f304f7c1c3d345', 'u3a7b78084e7d3956442d5a4d439dad51']);
        foreach ($accounts as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }
        $this->assertEquals('1.2.15', $accounts[0]->getRegistrar());
        $this->assertEquals('1.2.35', $accounts[1]->getId());
    }

    public function testListAllRelative(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_accounts",["",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListAccounts::responseToModel(new BaseResponse('{"id":3,"result":[["aaaaa.aaaaa","1.2.144"],["aaaaaaaaa","1.2.426"],["aaaaaabbbbbbb","1.2.128"],["aaaadsdsada","1.2.132"],["abcde","1.2.145"],["abcdef","1.2.175"],["abcdeh","1.2.176"],["acc-1","1.2.343"],["acc-10","1.2.352"],["acc-2","1.2.344"],["acc-3","1.2.345"],["acc-4","1.2.346"],["acc-5","1.2.347"],["acc-6","1.2.348"],["acc-7","1.2.349"],["acc-8","1.2.350"],["acc-9","1.2.351"],["addsadadasa","1.2.129"],["alaxio","1.2.55"],["all-txs","1.2.85"],["all-txs2","1.2.86"],["alx-customer-00d3afac-cb38-4eb6-955a-017e53630b21","1.2.584"],["alx-customer-01265aeb-2bdb-4caa-975e-83ced23365ae","1.2.249"],["alx-customer-02b2e883-9c41-4d72-beb0-b9275b5c5be9","1.2.476"],["alx-customer-02fb0082-719d-43ae-8f24-2e2302ff5f9b","1.2.11623"],["alx-customer-030bed20-c5d4-43d9-a2dc-b1d9366595c9","1.2.1123"],["alx-customer-03320597-b5be-4f30-b8e1-b3a001d72b50","1.2.223"],["alx-customer-03f858e4-f23b-4a64-acdf-f9abb96fee54","1.2.741"],["alx-customer-04a02a5b-5629-4d0b-a46e-84751b49747f","1.2.216"],["alx-customer-05479a4e-cc06-4fba-a7b1-8399a05576c3","1.2.976"],["alx-customer-05d2464b-8401-493e-8db1-25996bd2b49a","1.2.12119"],["alx-customer-05e81402-f4d4-47a3-8f11-9af15d3a2884","1.2.11804"],["alx-customer-076a1412-5745-4761-b19c-b8a736726f65","1.2.11703"],["alx-customer-08431fe7-7212-4a8e-a3c9-af4205492dbc","1.2.11887"],["alx-customer-0859fa21-7a22-4f77-956a-a330d508a820","1.2.11680"],["alx-customer-08bfd381-c250-49e5-8dc7-25c13ecac285","1.2.11934"],["alx-customer-0c7d8abc-1432-4041-9da8-0b0d859fa0cd","1.2.228"],["alx-customer-0cd159b4-bcbf-4564-ab45-6810d50a25cf","1.2.457"],["alx-customer-0d3c59dd-9c03-4df3-b4f1-1435c719d4f9","1.2.11635"],["alx-customer-0db55454-9fce-45d0-a8b8-309cb0f5d0d8","1.2.11964"],["alx-customer-0dbe344e-fba2-4697-a609-7bcddf7bc8a1","1.2.1121"],["alx-customer-0e1d51d8-64bc-461b-8f09-7a62ba498a2b","1.2.245"],["alx-customer-0ea1a063-ba89-450c-8ed7-c339388cd84c","1.2.685"],["alx-customer-0f2925c6-546a-4f64-abfd-37a994d28cfc","1.2.759"],["alx-customer-0f99b138-ec36-4192-a197-f87f41c76707","1.2.11896"],["alx-customer-10f4fca4-26f2-4849-9759-c55c73a35a97","1.2.651"],["alx-customer-11040c0a-6e0b-4009-8700-fb4e4e04b282","1.2.11713"],["alx-customer-1112c494-01d6-4c48-8509-7877939d5a1e","1.2.11597"],["alx-customer-123234db-869f-4f84-9b04-0a0b0889b812","1.2.11698"],["alx-customer-12b448c1-795b-467a-a89e-00504e140fbc","1.2.492"],["alx-customer-132246bb-ea85-4915-a676-97b1affdf09d","1.2.493"],["alx-customer-135ede0e-21b5-41ba-b667-c42b7f9cbbc1","1.2.1120"],["alx-customer-13b6054a-a8f5-48c7-998e-fa72fe0430c5","1.2.930"],["alx-customer-14703ff8-a0fb-49ab-942d-3e6d907b3a1c","1.2.220"],["alx-customer-14a9dc68-0ac1-417f-9dec-934bbf71c015","1.2.975"],["alx-customer-159add8a-95fe-4c25-89b9-18ebe1de7705","1.2.221"],["alx-customer-15a73167-a519-41c4-a852-23b8943617c7","1.2.233"],["alx-customer-16f6502e-1911-43b9-a7e4-fa6411fd063f","1.2.989"],["alx-customer-17800e97-5775-411e-9ec1-50822685b442","1.2.235"],["alx-customer-17f47e71-47e0-48e3-882a-1b6d72ccf4fa","1.2.196"],["alx-customer-187376ec-0686-4f41-9535-07ca9a81a4b2","1.2.260"],["alx-customer-188f023f-54b8-41c5-8464-964802af3aa1","1.2.11737"],["alx-customer-18e92505-8fab-45f3-a675-0f5e082bd6be","1.2.1125"],["alx-customer-18er2545-8fab-45f3-a675-0f5e082bd6be","1.2.1126"],["alx-customer-1b89144e-4f9a-4314-b40d-ec009216bd3a","1.2.11591"],["alx-customer-1d4d131d-d617-4812-a16d-73fa8b33cc3c","1.2.434"],["alx-customer-207067d4-2f2c-4275-866c-8bf5e2df6fff","1.2.11816"],["alx-customer-2178ed94-8ca5-4353-9139-97e18bf43d94","1.2.11702"],["alx-customer-21804c3a-f61a-44c4-b54b-1c2037796657","1.2.450"],["alx-customer-22de5454-373c-4436-bf7c-fd2f99b96478","1.2.12265"],["alx-customer-2399a7d6-b1bb-45ff-ad31-dec5103624a1","1.2.751"],["alx-customer-2506bd86-284a-40f6-9e17-592692a6f0ad","1.2.11644"],["alx-customer-251cb6cb-b999-4947-a672-01b3866a21ea","1.2.649"],["alx-customer-25207aeb-2abb-4b4b-9418-362a36617a37","1.2.191"],["alx-customer-25fc19ec-914b-458c-ba6a-d3aa7fd69529","1.2.258"],["alx-customer-265c0bfc-4fa8-4625-afc2-6fc88381671c","1.2.11604"],["alx-customer-269589e8-0906-476e-9226-2ea958a6e788","1.2.11593"],["alx-customer-26c32df5-4007-413c-b1ee-ee5e3ca46d97","1.2.11730"],["alx-customer-2731838d-2653-4507-ac89-0af0e4281e5d","1.2.448"],["alx-customer-276a0336-b783-4ed1-8aab-38df9f79c62d","1.2.11708"],["alx-customer-277a881c-d01a-48ad-9aa4-781f1f811a34","1.2.11789"],["alx-customer-290490a5-3320-4153-a953-2e9e6d48929d","1.2.589"],["alx-customer-29bdcb57-31e6-4bba-9f07-4951ada6f41d","1.2.11681"],["alx-customer-2ad44322-693b-4271-a222-622fb0c64666","1.2.248"],["alx-customer-2c2bae92-d6a0-4029-84b4-94f70582a09d","1.2.489"],["alx-customer-2c3963bd-69f6-4bc5-9d2e-2da88bb52342","1.2.11880"],["alx-customer-2d0211da-4c6d-4d46-9405-1fb9b56d9650","1.2.12063"],["alx-customer-2ddac1bf-6be2-40b9-bf78-f3d8516b2510","1.2.12152"],["alx-customer-2e425be5-ac58-4719-9055-e75fb2eb44c4","1.2.619"],["alx-customer-2e507fe3-4bab-46bc-be0b-0574034f9f12","1.2.744"],["alx-customer-2f3a0630-118c-4774-a146-a7aff7d9066e","1.2.256"],["alx-customer-30067c65-7a6a-47f5-8142-fbe59cee23f8","1.2.270"],["alx-customer-30de0a13-099b-4829-b717-8337dd340147","1.2.247"],["alx-customer-30e2dc86-57c8-46ce-a90a-e482096c867e","1.2.778"],["alx-customer-3116d24a-8a16-4ddb-8ddf-f64d8d166f07","1.2.461"],["alx-customer-32025581-4da4-4cdb-8a6e-8c321245f793","1.2.644"],["alx-customer-339dd198-ac4b-427d-be73-d9e6904b550b","1.2.441"],["alx-customer-34916e6d-4a26-40c2-957b-7caf6db48096","1.2.11658"],["alx-customer-34cec48f-f240-452d-9314-805ea39544d6","1.2.985"],["alx-customer-352d2f4c-d883-4752-bebb-2044a620f13e","1.2.406"]]}'))
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
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"search_accounts",["u961279ec8b7ae7bd62f304f7c1c3d345","","1.2.34",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    SearchAccounts::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.34","registrar":"1.2.15","name":"u961279ec8b7ae7bd62f304f7c1c3d345","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":["0:5","0:8"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.34","top_n_control_flags":0}]}'))
                ));
        }

        $accounts = $this->sdk->getAccountApi()->findAll('u961279ec8b7ae7bd62f304f7c1c3d345', '', '1.2.34', 1);

        $this->assertInternalType('array', $accounts);
        $this->assertCount(1, $accounts);

        $account = reset($accounts);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('1.2.34', $account->getId());
    }

    public function testSearchAccountHistory(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"search_account_history",["1.2.34","-time","0.0.0",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    SearchAccountHistory::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.17.54766","m_from_account":"1.2.34","m_to_account":"1.2.35","m_operation_type":0,"m_transaction_amount":{"amount":1500000,"asset_id":"1.3.0"},"m_transaction_fee":{"amount":500000,"asset_id":"1.3.0"},"m_str_description":"transfer","m_transaction_encrypted_memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"15500506128071447","message":"e912883dce55f7a60b29dda405531011fcd0583da5eade7c445d2b97c79afdde0cf8ed811ea6422ea8416cf852e461a28062d884f163b5264ec68e838819624d"},"m_timestamp":"2019-02-13T09:36:50"}]}'))
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

    public function testCreatetransfer(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     * @throws \Exception
     */
    public function testTransfer(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(11))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_accounts",[["1.2.35"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"extensions":[],"from":"1.2.34","to":"1.2.35","amount":{"amount":1500000,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","message":"'.$req->getParams()[0][0][1]['memo']['message'].'","nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[39,{"extensions":[],"from":"1.2.34","to":"1.2.35","amount":{"amount":1500000,"asset_id":"1.3.0"},"fee":{"amount":500000,"asset_id":"1.3.0"},"memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","message":"'.$req->getParams()[1]['operations'][0][1]['memo']['message'].'","nonce":"'.$req->getParams()[1]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":40579,"ref_block_prefix":3631349325,"expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(10)->toJson() === '{"jsonrpc":"2.0","id":10,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(11)->toJson() === '{"jsonrpc":"2.0","id":11,"method":"call","params":[6,"search_account_history",["1.2.34","-time","0.0.0",1]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.2.35","registrar":"1.2.15","name":"u3a7b78084e7d3956442d5a4d439dad51","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP",1]]},"options":{"memo_key":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.35","top_n_control_flags":0}]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":5,"result":{"id":"2.1.0","head_block_number":4234883,"head_block_id":"00409e834dfe71d81b1d897feabadafd5ba2253d","time":"2019-02-13T12:48:45","current_miner":"1.4.6","next_maintenance_time":"2019-02-14T00:00:00","last_budget_time":"2019-02-13T00:00:00","unspent_fee_budget":28586956,"mined_rewards":"279868000000","miner_budget_from_fees":50840244,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":10,"recently_missed_count":0,"current_aslot":10248186,"recent_slots_filled":"212323399330671546460679194953624321407","dynamic_flags":0,"last_irreversible_block_num":4234883}}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"id":9,"result":null}')),
                    Database::responseToModel(new BaseResponse('{"id":10,"result":6}')),
                    SearchAccountHistory::responseToModel(new BaseResponse('{"id":11,"result":[{"id":"2.17.54776","m_from_account":"1.2.34","m_to_account":"1.2.35","m_operation_type":0,"m_transaction_amount":{"amount":1500000,"asset_id":"1.3.0"},"m_transaction_fee":{"amount":500000,"asset_id":"1.3.0"},"m_str_description":"transfer","m_transaction_encrypted_memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"15500621254968895","message":"386fb34b9ea715ccbf94074bbdbb986f89ec7e2fd22692c490ff9ace66b8d58c097d8901615f7375edb3b52e8cae6531833a3fea2c7483edb4457db6920bd768"},"m_timestamp":"2019-02-13T12:48:45"}]}'))
                ));
        }

        $this->sdk->getAccountApi()->transfer(
            new Credentials(new ChainObject('1.2.34'), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)),
            '1.2.35',
            (new AssetAmount())->setAmount(1500000),
            'hello memo here i am',
            false
        );

        /** @var TransactionDetail[] $transactions */
        $transactions = $this->sdk->getAccountApi()->searchAccountHistory(new ChainObject('1.2.34'), '0.0.0', SearchAccountHistory::ORDER_TIME_DESC, 1);
        $lastTransaction = reset($transactions);
        $this->assertInstanceOf(TransactionDetail::class, $lastTransaction);
        $this->assertEquals('1.2.34', $lastTransaction->getFrom());
        $this->assertEquals('1.2.35', $lastTransaction->getTo());
        $this->assertEquals('1.3.0', $lastTransaction->getAmount()->getAssetId());
        $this->assertEquals(1500000, $lastTransaction->getAmount()->getAmount());
    }

    public function testDerivePrivateKey()
    {
        $privateKey = $this->sdk->getAccountApi()->derivePrivateKey(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertInstanceOf(PrivateKey::class, $privateKey);
        $this->assertEquals('8db568dae0e0d3708649e6cccf94c71909488f01c92369c28fdb090e40203f93', $privateKey->toHex());
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
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_required_fees",[[[1,{"fee":{"amount":0,"asset_id":"1.3.0"},"registrar":"1.2.34","name":"'.$req->getParams()[0][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[7,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[1,{"fee":{"amount":500000,"asset_id":"1.3.0"},"registrar":"1.2.34","name":"'.$req->getParams()[1]['operations'][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"ref_block_num":41571,"ref_block_prefix":3758552026,"expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[6,"get_account_by_name",["'.$req->getParams()[0].'"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":4235875,"head_block_id":"0040a263daf306e0329735fd8ed255fe5b481033","time":"2019-02-13T14:28:55","current_miner":"1.4.8","next_maintenance_time":"2019-02-14T00:00:00","last_budget_time":"2019-02-13T00:00:00","unspent_fee_budget":25668492,"mined_rewards":"316572000000","miner_budget_from_fees":50840244,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":12,"recently_missed_count":0,"current_aslot":10249388,"recent_slots_filled":"317672503911711937783877557590480124671","dynamic_flags":0,"last_irreversible_block_num":4235875}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":5,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":6,"result":7}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"id":7,"result":null}')),
                    Database::responseToModel(new BaseResponse('{"id":8,"result":6}')),
                    GetAccountByName::responseToModel(new BaseResponse('{"id":9,"result":{"id":"1.2.12584","registrar":"1.2.34","name":"'.$accountName.'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz",1]]},"options":{"memo_key":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.12584","top_n_control_flags":0}}'))
                ));
        }

        $this->sdk->getAccountApi()->registerAccount(
            $accountName,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            DCoreSDKTest::PUBLIC_KEY_1,
            new ChainObject('1.2.34'),
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
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_required_fees",[[[1,{"fee":{"amount":0,"asset_id":"1.3.0"},"registrar":"1.2.34","name":"'.$req->getParams()[0][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[7,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[1,{"fee":{"amount":500000,"asset_id":"1.3.0"},"registrar":"1.2.34","name":"'.$req->getParams()[1]['operations'][0][1]['name'].'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"extensions":[]}]],"ref_block_num":41571,"ref_block_prefix":3758552026,"expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[6,"get_account_by_name",["'.$req->getParams()[0].'"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":4235875,"head_block_id":"0040a263daf306e0329735fd8ed255fe5b481033","time":"2019-02-13T14:28:55","current_miner":"1.4.8","next_maintenance_time":"2019-02-14T00:00:00","last_budget_time":"2019-02-13T00:00:00","unspent_fee_budget":25668492,"mined_rewards":"316572000000","miner_budget_from_fees":50840244,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":12,"recently_missed_count":0,"current_aslot":10249388,"recent_slots_filled":"317672503911711937783877557590480124671","dynamic_flags":0,"last_irreversible_block_num":4235875}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":5,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":6,"result":7}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"id":7,"result":null}')),
                    Database::responseToModel(new BaseResponse('{"id":8,"result":6}')),
                    GetAccountByName::responseToModel(new BaseResponse('{"id":9,"result":{"id":"1.2.12586","registrar":"1.2.34","name":"'.$accountName.'","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw",1]]},"options":{"memo_key":"DCT6BU82XJnfLLtBYsweEVSSsEy3fNNNuZoWxV2mcESjvrH5cLLtw","voting_account":"1.2.3","num_miner":0,"votes":[],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.12584","top_n_control_flags":0}}'))
                ));
        }

        $this->sdk->getAccountApi()->createAccountWithBrainKey(
            'FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING',
            $accountName,
            new ChainObject('1.2.34'),
            DCoreSDKTest::PRIVATE_KEY_1
        );

        $account = $this->sdk->getAccountApi()->getByName($accountName);

        $this->assertEquals($accountName, $account->getName());
    }
}