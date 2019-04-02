<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountHistory;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRecentTransactionById;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\GetTransactionById;
use DCorePHP\Net\Model\Request\History;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class TransactionApiTest extends DCoreSDKTest
{

    public function testCreateTransaction(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateTransactionSingleOperation(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllProposed(): void
    {
        // TODO: No data
        $this->sdk->getTransactionApi()->getAllProposed(new ChainObject('1.2.34'));
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
//        $transaction = $this->sdk->getTransactionApi()->getRecent('322d451fb1dc9b3ec6bc521395f4547a8b62eb3f');
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_transaction_by_id",["322d451fb1dc9b3ec6bc521395f4547a8b62eb3f"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTransactionById::responseToModel(new BaseResponse('{"id":3,"result":{"ref_block_num":65525,"ref_block_prefix":2304643484,"expiration":"2018-10-13T12:37:19","operations":[[39,{"fee":{"amount":500000,"asset_id":"1.3.0"},"from":"1.2.34","to":"1.2.35","amount":{"amount":1,"asset_id":"1.3.0"},"memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"735604672334802432","message":"4bc2a1ee670302ceddb897c2d351fa0496ff089c934e35e030f8ae4f3f9397a7"},"extensions":[]}]],"extensions":[],"signatures":["2072e8b8efa1ca97c2f9d85f69a31761fc212858fc77b5d8bc824627117904214458a7deecc8b4fd8495f8b448d971ed92c0bcb0c9b3f3fcf0c7eba4c81303de4b"]}}'))
                ));
        }

        $transaction = $this->sdk->getTransactionApi()->getById('322d451fb1dc9b3ec6bc521395f4547a8b62eb3f');
        $this->assertEquals(65525, $transaction->getRefBlockNum());
        $this->assertEquals('2072e8b8efa1ca97c2f9d85f69a31761fc212858fc77b5d8bc824627117904214458a7deecc8b4fd8495f8b448d971ed92c0bcb0c9b3f3fcf0c7eba4c81303de4b', $transaction->getSignatures()[0]);
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_transaction",["1370282","0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTransaction::responseToModel(new BaseResponse('{"id":3,"result":{"ref_block_num":59561,"ref_block_prefix":2414941591,"expiration":"2018-07-26T11:27:07","operations":[[39,{"fee":{"amount":500000,"asset_id":"1.3.0"},"from":"1.2.34","to":"1.2.35","amount":{"amount":1500000,"asset_id":"1.3.0"},"memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"735604672334802432","message":"4bc2a1ee670302ceddb897c2d351fa0496ff089c934e35e030f8ae4f3f9397a7"},"extensions":[]}]],"extensions":[],"signatures":["1f140e5744bcef282147ef3f0bab8df46f49704a99046d6ea5db37ab3113e0f45935fd94af7b33189ad34fa1666ab7e54aa127d725e2018fb6b68771aacef54c41"],"operation_results":[[0,{}]]}}'))
                ));
        }

        $transaction = $this->sdk->getTransactionApi()->getByBlockNum(1370282, 0);
        $this->assertEquals('1f140e5744bcef282147ef3f0bab8df46f49704a99046d6ea5db37ab3113e0f45935fd94af7b33189ad34fa1666ab7e54aa127d725e2018fb6b68771aacef54c41', $transaction->getSignatures()[0]);
        $this->assertEquals(59561, $transaction->getRefBlockNum());
    }


    public function testGetByConfirmation(): void
    {
        // TODO: Missing test in kotlin
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    public function testGetHexDump(): void
    {
//        $id = $this->sdk->getGeneralApi()->getChainId();
//        $ptrx = $this->sdk->getTransactionApi()->getByBlockNum(1370282, 0);
//        $trx = new Transaction(); // TODO: Transaction model differs from Kotlin's Transaction model
//        $hexDump = $this->sdk->getTransactionApi()->getHexDump();
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testIsConfirmed(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"history",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_history",["1.2.34","1.7.59658",100,"1.7.59659"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[7,"get_dynamic_global_properties",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    History::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountHistory::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.7.59659","op":[39,{"fee":{"amount":500000,"asset_id":"1.3.0"},"from":"1.2.34","to":"1.2.35","amount":{"amount":1500000,"asset_id":"1.3.0"},"memo":{"from":"DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz","to":"DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP","nonce":"15500506128071447","message":"e912883dce55f7a60b29dda405531011fcd0583da5eade7c445d2b97c79afdde0cf8ed811ea6422ea8416cf852e461a28062d884f163b5264ec68e838819624d"},"extensions":[]}],"result":[0,{}],"block_num":4232995,"trx_in_block":0,"op_in_trx":0,"virtual_op":63253}]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":7}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":5,"result":{"id":"2.1.0","head_block_number":4744275,"head_block_id":"0048645379552980bdf1e59817f5061ae74ffa84","time":"2019-03-21T10:37:00","current_miner":"1.4.2","next_maintenance_time":"2019-03-22T00:00:00","last_budget_time":"2019-03-21T00:00:00","unspent_fee_budget":27126919,"mined_rewards":"232693000000","miner_budget_from_fees":42648171,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":8,"recently_missed_count":2,"current_aslot":10868577,"recent_slots_filled":"339780985734442856302679874814441644027","dynamic_flags":0,"last_irreversible_block_num":4744275}}'))
                ));
        }

        $this->assertTrue($this->sdk->getTransactionApi()->isConfirmed(
            new ChainObject('1.2.34'),
            new ChainObject('1.7.59659')
        ));
    }
}