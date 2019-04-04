<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountBalances;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class BalanceApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGet(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_balances",["1.2.34",["1.3.56576"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":0,"asset_id":"1.3.56576"}]}'))
                ));
        }

        $asset = $this->sdk->getBalanceApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.3.56576'));

        $this->assertEquals('1.3.56576', $asset->getAssetId()->getId());
        $this->assertEquals(0, $asset->getAmount());
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_account_balances",["1.2.34",[]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":"19098589442","asset_id":"1.3.0"},{"amount":101,"asset_id":"1.3.44"},{"amount":979600000,"asset_id":"1.3.53"},{"amount":"4764856000","asset_id":"1.3.54"}]}'))
                ));
        }

        /** @var AssetAmount[] $balances */
        $balances = $this->sdk->getBalanceApi()->getAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        $this->assertInternalType('array', $balances);

        foreach ($balances as $balance) {
            $this->assertRegExp('/^\d+\.\d+\.\d+$/', $balance->getAssetId());
            $this->assertRegExp('/^\d+$/', $balance->getAmount());
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_named_account_balances",["u961279ec8b7ae7bd62f304f7c1c3d345",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":"18437730145","asset_id":"1.3.0"}]}'))
                ));
        }

        $asset = $this->sdk->getBalanceApi()->getByName('u961279ec8b7ae7bd62f304f7c1c3d345', new ChainObject('1.3.0'));

        $this->assertEquals('1.3.0', $asset->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_named_account_balances",["u961279ec8b7ae7bd62f304f7c1c3d345",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":"18437730145","asset_id":"1.3.0"}]}'))
                ));
        }

        /** @var AssetAmount[] $assets */
        $assets = $this->sdk->getBalanceApi()->getAllByName('u961279ec8b7ae7bd62f304f7c1c3d345', [new ChainObject('1.3.0')]);

        $this->assertEquals('1.3.0', $assets[0]->getAssetId()->getId());
    }

    public function testGetWithAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo Missing kotlin test
//        $assets = $this->sdk->getBalanceApi()->getWithAsset(new ChainObject('1.2.34'), 'DCT');
    }

    public function testGetAllWithAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo Missing kotlin test
//        $assets = $this->sdk->getBalanceApi()->getAllWithAsset(new ChainObject('1.2.34'), ['DCT', 'DCT']);
    }

    public function testGetWithAssetByName(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllWithAssetByName(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllVesting(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo Missing test data
//        $assets = $this->sdk->getBalanceApi()->getAllVesting(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
    }
}