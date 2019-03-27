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
    public function testGet(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
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
        $balances = $this->sdk->getBalanceApi()->getAll(new ChainObject('1.2.34'));

        $this->assertInternalType('array', $balances);

        foreach ($balances as $balance) {
            $this->assertRegExp('/^\d+\.\d+\.\d+$/', $balance->getAssetId());
            $this->assertRegExp('/^\d+$/', $balance->getAmount());
        }
    }

    public function testGetByName(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllByName(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetWithAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllWithAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
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
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}