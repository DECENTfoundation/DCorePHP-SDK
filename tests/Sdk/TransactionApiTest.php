<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountHistory;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRecentTransactionById;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\GetTransactionById;
use DCorePHP\Net\Model\Request\GetTransactionHex;
use DCorePHP\Net\Model\Request\History;
use DCorePHP\Net\Model\Request\Login;
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(7))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":671086,"head_block_id":"000a3d6e57c94ca100f0d69c72f5ee3332f12be7","time":"2019-04-23T08:55:25","current_miner":"1.4.7","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2587095,"mined_rewards":"237614000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068305,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671086}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testCreateTransactionSingleOperation(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(7))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":671163,"head_block_id":"000a3dbbd7dd6b726494dfab37e13faaa1fc2548","time":"2019-04-23T09:01:50","current_miner":"1.4.1","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2568769,"mined_rewards":"240463000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068382,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671163}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = $this->sdk->getTransactionApi()->createTransactionSingleOperation($operation);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllProposed(): void
    {
//        $this->sdk->getTransactionApi()->getAllProposed(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetRecent(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_recent_transaction_by_id",["322d451fb1dc9b3ec6bc521395f4547a8b62eb3f"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetRecentTransactionById::responseToModel(new BaseResponse('{"id":3,"result":null}'))
                ));
        }

        // TODO: Test response
//        $transaction = $this->sdk->getTransactionApi()->getRecent('abb2c83679c2217bd20bed723f3a9ffa8653a953');
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetById(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_transaction_by_id",["abb2c83679c2217bd20bed723f3a9ffa8653a953"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTransactionById::responseToModel(new BaseResponse('{"id":3,"result":{"ref_block_num":53315,"ref_block_prefix":2909649531,"expiration":"2019-04-10T08:59:50","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":1,"asset_id":"1.3.0"},"memo":{"from":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","to":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","nonce":"735604672334802432","message":"16c0edb3d24d8914ab4a42b53b3a485c6376da9d2063ee552a6c3f86ca5229ce"},"extensions":[]}]],"extensions":[],"signatures":["1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20"]}}'))
                ));
        }

        $transaction = $this->sdk->getTransactionApi()->getById('abb2c83679c2217bd20bed723f3a9ffa8653a953');
        $this->assertEquals(53315, $transaction->getRefBlockNum());
        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $transaction->getSignatures()[0]);
    }

    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetByBlockNum(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_transaction",["446532","0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTransaction::responseToModel(new BaseResponse('{"id":3,"result":{"ref_block_num":53315,"ref_block_prefix":2909649531,"expiration":"2019-04-10T08:59:50","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":1,"asset_id":"1.3.0"},"memo":{"from":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","to":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","nonce":"735604672334802432","message":"16c0edb3d24d8914ab4a42b53b3a485c6376da9d2063ee552a6c3f86ca5229ce"},"extensions":[]}]],"extensions":[],"signatures":["1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20"],"operation_results":[[0,{}]]}}'))
                ));
        }

        $transaction = $this->sdk->getTransactionApi()->getByBlockNum(446532, 0);
        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $transaction->getSignatures()[0]);
        $this->assertEquals('1.2.27', $transaction->getOperations()[0]->getFrom()->getId());
    }


    /**
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     */
    public function testGetByConfirmation(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_transaction",["446532","0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_transaction",["446532","0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTransaction::responseToModel(new BaseResponse('{"id":3,"result":{"ref_block_num":53315,"ref_block_prefix":2909649531,"expiration":"2019-04-10T08:59:50","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":1,"asset_id":"1.3.0"},"memo":{"from":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","to":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","nonce":"735604672334802432","message":"16c0edb3d24d8914ab4a42b53b3a485c6376da9d2063ee552a6c3f86ca5229ce"},"extensions":[]}]],"extensions":[],"signatures":["1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20"],"operation_results":[[0,{}]]}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetTransaction::responseToModel(new BaseResponse('{"id":5,"result":{"ref_block_num":53315,"ref_block_prefix":2909649531,"expiration":"2019-04-10T08:59:50","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":1,"asset_id":"1.3.0"},"memo":{"from":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","to":"DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp","nonce":"735604672334802432","message":"16c0edb3d24d8914ab4a42b53b3a485c6376da9d2063ee552a6c3f86ca5229ce"},"extensions":[]}]],"extensions":[],"signatures":["1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20"],"operation_results":[[0,{}]]}}'))
                ));
        }

        $transaction = $this->sdk->getTransactionApi()->getByBlockNum(446532, 0);
        $transactionConfirmation = new TransactionConfirmation();
        $transactionConfirmation
            ->setId('abb2c83679c2217bd20bed723f3a9ffa8653a953')
            ->setBlockNum('446532')
            ->setTransaction($transaction)
            ->setTrxNum('0');
        $trxByConfirmation = $this->sdk->getTransactionApi()->getByConfirmation($transactionConfirmation);

        $this->assertEquals('1f6083f0939790223832e806e1bbc04612eee8d592061029b6c5ea40fbe712777c1ddfc46db934b17cd6b585f38d183d3d9b274d44371901d7f43ee7ce03e67a20', $trxByConfirmation->getSignatures()[0]);
        $this->assertEquals(53315, $trxByConfirmation->getRefBlockNum());

    }


    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testGetHexDump(): void
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[6,"get_transaction_hex",[{}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":671300,"head_block_id":"000a3e448479d031e449b27717c6bdb0ca13dcc1","time":"2019-04-23T09:13:15","current_miner":"1.4.1","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2536163,"mined_rewards":"245532000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068519,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671300}}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":7,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    Database::responseToModel(new BaseResponse('{"id":8,"result":6}')),
                    GetTransactionHex::responseToModel(new BaseResponse('{"id":9,"result":"00000000000000000000000000"}'))

                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $transaction = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $res = $this->sdk->getTransactionApi()->getHexDump($transaction);

        $this->assertEquals('00000000000000000000000000', $res);
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \DCorePHP\Model\InvalidOperationTypeException
     * @throws \DCorePHP\Net\Model\Request\CouldNotParseOperationTypeException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testIsConfirmed(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"history",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_history",["1.2.27","1.7.919361",100,"1.7.919362"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[7,"get_dynamic_global_properties",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    History::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountHistory::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.7.919362","op":[42,{"fee":{"amount":0,"asset_id":"1.3.0"},"author":"1.2.27","escrow":{"amount":1000000,"asset_id":"1.3.0"},"content":"2.13.167"}],"result":[0,{}],"block_num":630110,"trx_in_block":0,"op_in_trx":1,"virtual_op":6009}]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":7}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":5,"result":{"id":"2.1.0","head_block_number":671012,"head_block_id":"000a3d240367fe3be570da2c7366c93e2fd81bbd","time":"2019-04-23T08:49:15","current_miner":"1.4.9","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2604707,"mined_rewards":"234876000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068231,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671012}}'))
                ));
        }

        $this->assertTrue($this->sdk->getTransactionApi()->isConfirmed(
            new ChainObject(DCoreSDKTest::ACCOUNT_ID_1),
            new ChainObject('1.7.919362')
        ));
    }
}
