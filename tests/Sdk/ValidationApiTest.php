<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BlockData;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\InvalidOperationTypeException;
use DCorePHP\Model\Operation\ProposalCreate;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetPotentialSignatures;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\GetRequiredSignatures;
use DCorePHP\Net\Model\Request\GetTransaction;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\ValidateTransaction;
use DCorePHP\Net\Model\Request\VerifyAccountAuthority;
use DCorePHP\Net\Model\Request\VerifyAuthority;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use InvalidArgumentException;

class ValidationApiTest extends DCoreSDKTest
{
    /**
     * @throws \Exception
     */
    public function testGetRequiredSignatures(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(4))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_required_signatures",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":16044,"ref_block_prefix":"474550214","expiration":"2019-04-23T09:22:25","signatures":[]},["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":671404,"head_block_id":"000a3eacc60f491c1dc64fbd6f7a78c495a3a9fa","time":"2019-04-23T09:21:55","current_miner":"1.4.8","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2511411,"mined_rewards":"249380000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068623,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671404}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    GetRequiredSignatures::responseToModel(new BaseResponse('{"id":4,"result":["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb"]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $oldTrx = $this->sdk->getTransactionApi()->createTransaction([$operation]);

        $blockData = new BlockData($oldTrx->getBlockData()->getRefBlockNum(), $oldTrx->getBlockData()->getRefBlockPrefix(), $oldTrx->getBlockData()->getExpiration());
        $trx = new Transaction();
        $trx->setBlockData($blockData)->setOperations($oldTrx->getOperations());

        $sigs = $this->sdk->getValidationApi()->getRequiredSignatures($trx, [Address::decode(DCoreSDKTest::PUBLIC_KEY_1), Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]);
        $this->assertContains('DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb', $sigs);
    }

    /**
     * @throws \Exception
     */
    public function testGetPotentialSignatures(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(4))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_potential_signatures",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":16123,"ref_block_prefix":"342315500","expiration":"2019-04-23T09:29:00","signatures":[]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":671483,"head_block_id":"000a3efbec51671403e1c887db0a160ca91d663b","time":"2019-04-23T09:28:30","current_miner":"1.4.1","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2492609,"mined_rewards":"252303000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068702,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671483}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    GetRequiredSignatures::responseToModel(new BaseResponse('{"id":4,"result":["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb"]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $oldTrx = $this->sdk->getTransactionApi()->createTransaction([$operation]);

        $blockData = new BlockData($oldTrx->getBlockData()->getRefBlockNum(), $oldTrx->getBlockData()->getRefBlockPrefix(), $oldTrx->getBlockData()->getExpiration());
        $trx = new Transaction();
        $trx->setBlockData($blockData)->setOperations($oldTrx->getOperations());

        $sigs = $this->sdk->getValidationApi()->getPotentialSignatures($trx);
        $this->assertContains('DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb', $sigs);
    }

    /**
     * @throws \Exception
     */
    public function testVerifyAuthorityTrue(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(4))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"verify_authority",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":16175,"ref_block_prefix":"418285910","expiration":"'.$req->getParams()[0]['expiration'].'","signatures":["'.$req->getParams()[0]['signatures'][0].'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":671535,"head_block_id":"000a3f2f5689ee18c1c6dafc87f208eae139b88a","time":"2019-04-23T09:32:50","current_miner":"1.4.3","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2480233,"mined_rewards":"254227000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068754,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671535}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    VerifyAuthority::responseToModel(new BaseResponse('{"id":4,"result":true}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $trx->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertTrue($this->sdk->getValidationApi()->verifyAuthority($trx));
    }

    /**
     * @throws \Exception
     */
    public function testVerifyAuthorityFalse(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(4))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"verify_authority",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":16175,"ref_block_prefix":"418285910","expiration":"'.$req->getParams()[0]['expiration'].'","signatures":["'.$req->getParams()[0]['signatures'][0].'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":671535,"head_block_id":"000a3f2f5689ee18c1c6dafc87f208eae139b88a","time":"2019-04-23T09:32:50","current_miner":"1.4.3","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2480233,"mined_rewards":"254227000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068754,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671535}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    VerifyAuthority::responseToModel(new BaseResponse('{"id":4,"result":false}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $trx->sign(DCoreSDKTest::PRIVATE_KEY_2);

        $this->assertFalse($this->sdk->getValidationApi()->verifyAuthority($trx));
    }

    public function testVerifyAccountAuthority(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"verify_account_authority",["public-account-10",["DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    VerifyAccountAuthority::responseToModel(new BaseResponse('{"id":1,"result":true}'))
                ));
        }

        $this->assertTrue($this->sdk->getValidationApi()->verifyAccountAuthority(DCoreSDKTest::ACCOUNT_NAME_2, [Address::decode(DCoreSDKTest::PUBLIC_KEY_2)]));
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @throws \Exception
     */
    public function testValidateTransaction(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(4))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"validate_transaction",[{"extensions":[],"operations":[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":100000,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0]['operations'][0][1]['memo']['nonce'].'"}}]],"ref_block_num":16332,"ref_block_prefix":"2566660774","expiration":"'.$req->getParams()[0]['expiration'].'","signatures":["'.$req->getParams()[0]['signatures'][0].'"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":671692,"head_block_id":"000a3fcca622fc9893c65b97427532087d1e170d","time":"2019-04-23T09:45:55","current_miner":"1.4.10","next_maintenance_time":"2019-04-24T00:00:00","last_budget_time":"2019-04-23T00:00:00","unspent_fee_budget":2442867,"mined_rewards":"260036000000","miner_budget_from_fees":4115531,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1068911,"recent_slots_filled":"340282366920938463463374607431768211455","dynamic_flags":0,"last_irreversible_block_num":671692}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    ValidateTransaction::responseToModel(new BaseResponse('{"id":4,"result":{"ref_block_num":16332,"ref_block_prefix":2566660774,"expiration":"2019-04-23T09:46:29","operations":[[39,{"fee":{"amount":100000,"asset_id":"1.3.0"},"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"memo":{"from":"DCT1111111111111111111111111111111114T1Anm","to":"DCT1111111111111111111111111111111114T1Anm","nonce":"952359549871654905","message":""},"extensions":[]}]],"extensions":[],"signatures":["1f107dfd5a55a6382b73bcd6c55cba5970f1229f086d8021a1fb60780724e33f6478461a9f18983e8b8738c67859f7567309f4d28fa4a575f3a6d24e9c58250aab"],"operation_results":[[0,{}]]}}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));
        $trx = $this->sdk->getTransactionApi()->createTransaction([$operation]);
        $trx->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->sdk->getValidationApi()->validateTransaction($trx);

        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFees(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":100000,"asset_id":"1.3.0"}]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));

        $fees = $this->sdk->getValidationApi()->getFees([$operation]);
        /** @var AssetAmount $fee */
        $fee = reset($fees);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFee(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_required_fees",[[[39,{"from":"1.2.27","to":"1.2.28","amount":{"amount":10,"asset_id":"1.3.0"},"fee":{"amount":0,"asset_id":"1.3.0"},"memo":{"message":null,"nonce":"'.$req->getParams()[0][0][1]['memo']['nonce'].'"}}]],"1.3.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":100000,"asset_id":"1.3.0"}]}'))
                ));
        }

        $operation = new Transfer2();
        $operation
            ->setFrom(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setTo(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2))
            ->setAmount((new AssetAmount())->setAmount(10));

        $fee = $this->sdk->getValidationApi()->getFee($operation);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }

    public function testGetFeeByType(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_required_fees",[[[]],"1.3.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":100000,"asset_id":"1.3.0"}]}'))
                ));
        }

        $fee = $this->sdk->getValidationApi()->getFeeByType(Transfer2::OPERATION_TYPE);

        $this->assertEquals(100000, $fee->getAmount());
        $this->assertEquals('1.3.0', $fee->getAssetId()->getId());
    }
}