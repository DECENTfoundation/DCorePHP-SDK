<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransaction;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\NetworkBroadcast;
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0]['operations'][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"ref_block_num":63455,"ref_block_prefix":"289160702","expiration":"'. $req->getParams()[0]['expiration'] .'","signatures":["'. $req->getParams()[0]['signatures'][0] .'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":587743,"head_block_id":"0008f7dffe3d3c1179f1de1d24d0643b419962df","time":"2019-04-18T13:08:55","current_miner":"1.4.6","next_maintenance_time":"2019-04-19T00:00:00","last_budget_time":"2019-04-18T00:00:00","unspent_fee_budget":1638212,"mined_rewards":"350168000000","miner_budget_from_fees":3616188,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":59,"recently_missed_count":0,"current_aslot":984962,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":587743}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransaction::responseToModel(new BaseResponse('{"id":9,"result":null}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = $this->sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $transaction = $this->sdk->getTransactionApi()->createTransaction([$transfer]);
        $transaction->sign($credentials->getKeyPair()->getPrivate()->toWif());

        $this->sdk->getBroadcastApi()->broadcast($transaction);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws \Exception
     */
    public function testBroadcastOperationsWithECKeyPair(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.28","to":"1.2.27","amount":{"amount":'. $req->getParams()[0][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.28","to":"1.2.27","amount":{"amount":'. $req->getParams()[0]['operations'][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"ref_block_num":63478,"ref_block_prefix":"491382354","expiration":"'. $req->getParams()[0]['expiration'] .'","signatures":["'. $req->getParams()[0]['signatures'][0] .'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":587766,"head_block_id":"0008f7f652e6491d6bfbf2d1e537c39972fb3689","time":"2019-04-18T13:10:50","current_miner":"1.4.3","next_maintenance_time":"2019-04-19T00:00:00","last_budget_time":"2019-04-18T00:00:00","unspent_fee_budget":1633405,"mined_rewards":"351019000000","miner_budget_from_fees":3616188,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":59,"recently_missed_count":0,"current_aslot":984985,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":587766}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransaction::responseToModel(new BaseResponse('{"id":9,"result":null}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = $this->sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $this->sdk->getBroadcastApi()->broadcastOperationsWithECKeyPair($credentials->getKeyPair(), [$transfer]);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testBroadcastOperationWithECKeyPair(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0]['operations'][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"ref_block_num":63514,"ref_block_prefix":"730152048","expiration":"'. $req->getParams()[0]['expiration'] .'","signatures":["'. $req->getParams()[0]['signatures'][0] .'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":587802,"head_block_id":"0008f81a703c852bce56fe8153770bee077b648f","time":"2019-04-18T13:13:50","current_miner":"1.4.11","next_maintenance_time":"2019-04-19T00:00:00","last_budget_time":"2019-04-18T00:00:00","unspent_fee_budget":1625881,"mined_rewards":"352351000000","miner_budget_from_fees":3616188,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":59,"recently_missed_count":0,"current_aslot":985021,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":587802}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransaction::responseToModel(new BaseResponse('{"id":9,"result":null}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = $this->sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $this->sdk->getBroadcastApi()->broadcastOperationWithECKeyPair($credentials->getKeyPair(), $transfer);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testBroadcastOperationsWithPrivateKey(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.28","to":"1.2.27","amount":{"amount":'. $req->getParams()[0][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.28","to":"1.2.27","amount":{"amount":'. $req->getParams()[0]['operations'][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"ref_block_num":63478,"ref_block_prefix":"491382354","expiration":"'. $req->getParams()[0]['expiration'] .'","signatures":["'. $req->getParams()[0]['signatures'][0] .'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":587766,"head_block_id":"0008f7f652e6491d6bfbf2d1e537c39972fb3689","time":"2019-04-18T13:10:50","current_miner":"1.4.3","next_maintenance_time":"2019-04-19T00:00:00","last_budget_time":"2019-04-18T00:00:00","unspent_fee_budget":1633405,"mined_rewards":"351019000000","miner_budget_from_fees":3616188,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":59,"recently_missed_count":0,"current_aslot":984985,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":587766}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransaction::responseToModel(new BaseResponse('{"id":9,"result":null}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        $transfer = $this->sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_1,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $this->sdk->getBroadcastApi()->broadcastOperationsWithPrivateKey($credentials->getKeyPair()->getPrivate()->toWif(), [$transfer]);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testBroadcastOperationWithPrivateKey(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(9))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[7,"broadcast_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":'. $req->getParams()[0]['operations'][0][1]['amount']['amount'] .',"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":"0000000041686f7920504850","nonce":"0"}}]],"ref_block_num":63547,"ref_block_prefix":"1411438435","expiration":"'. $req->getParams()[0]['expiration'] .'","signatures":["'. $req->getParams()[0]['signatures'][0] .'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":587835,"head_block_id":"0008f83b63d72054f4bbd39c5647cf00747c0e9b","time":"2019-04-18T13:16:35","current_miner":"1.4.11","next_maintenance_time":"2019-04-19T00:00:00","last_budget_time":"2019-04-18T00:00:00","unspent_fee_budget":1618984,"mined_rewards":"353572000000","miner_budget_from_fees":3616188,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":59,"recently_missed_count":0,"current_aslot":985054,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":587835}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":8,"result":7}')),
                    BroadcastTransaction::responseToModel(new BaseResponse('{"id":9,"result":null}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $transfer = $this->sdk->getAccountApi()->createTransfer(
            $credentials,
            DCoreSDKTest::ACCOUNT_ID_2,
            (new AssetAmount())->setAmount(mt_rand()),
            'Ahoy PHP',
            false);

        $this->sdk->getBroadcastApi()->broadcastOperationWithPrivateKey($credentials->getKeyPair()->getPrivate()->toWif(), $transfer);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
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