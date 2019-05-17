<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Explorer\VestingBalance;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountBalances;
use DCorePHP\Net\Model\Request\GetVestingBalances;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\LookupAssets;
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
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_account_balances",["1.2.27",["1.3.56576"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":0,"asset_id":"1.3.56576"}]}'))
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
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_account_balances",["1.2.27",[]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":"995270224334","asset_id":"1.3.0"},{"amount":990,"asset_id":"1.3.36"}]}'))
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
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_named_account_balances",["public-account-9",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":"995270224334","asset_id":"1.3.0"}]}'))
                ));
        }

        $asset = $this->sdk->getBalanceApi()->getByName(DCoreSDKTest::ACCOUNT_NAME_1, new ChainObject('1.3.0'));

        $this->assertEquals('1.3.0', $asset->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_named_account_balances",["public-account-9",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":1,"result":[{"amount":"995270224334","asset_id":"1.3.0"}]}'))
                ));
        }

        /** @var AssetAmount[] $assets */
        $assets = $this->sdk->getBalanceApi()->getAllByName(DCoreSDKTest::ACCOUNT_NAME_1, [new ChainObject('1.3.0')]);

        $this->assertEquals('1.3.0', $assets[0]->getAssetId()->getId());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetWithAsset(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(2))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_account_balances",["1.2.34",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":2,"result":[{"amount":"18437730145","asset_id":"1.3.0"}]}'))
                ));
        }

        [$asset, $assetAmount] = $this->sdk->getBalanceApi()->getWithAsset(new ChainObject('1.2.34'));

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    public function testGetAllWithAsset(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(2))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT","DCT"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_account_balances",["1.2.34",["1.3.0","1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"},{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":2,"result":[{"amount":"18437730145","asset_id":"1.3.0"}]}'))
                ));
        }

        $assetPairs = $this->sdk->getBalanceApi()->getAllWithAsset(new ChainObject('1.2.34'), ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    public function testGetWithAssetByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(2))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_named_account_balances",["public-account-9",["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":2,"result":[{"amount":"995270224334","asset_id":"1.3.0"}]}'))
                ));
        }

        [$asset, $assetAmount] = $this->sdk->getBalanceApi()->getWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1);

        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
    }

    public function testGetAllWithAssetByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(2))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT","DCT"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_named_account_balances",["public-account-9",["1.3.0","1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"},{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}')),
                    GetAccountBalances::responseToModel(new BaseResponse('{"id":5,"result":[{"amount":"995270224334","asset_id":"1.3.0"}]}'))
                ));
        }

        $assetPairs = $this->sdk->getBalanceApi()->getAllWithAssetByName(DCoreSDKTest::ACCOUNT_NAME_1, ['DCT', 'DCT']);

        foreach ($assetPairs as [$asset, $assetAmount]) {
            $this->assertEquals('DCT', $asset->getSymbol());
            $this->assertEquals('1.3.0', $asset->getId()->getId());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId()->getId());
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetAllVesting(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_vesting_balances",["1.2.4"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetVestingBalances::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.9.6","owner":"1.2.4","balance":{"amount":"19787929980529","asset_id":"1.3.0"},"policy":[1,{"vesting_seconds":86400,"start_claim":"1970-01-01T00:00:00","coin_seconds_earned":"1709673948751708800","coin_seconds_earned_last_update":"2019-04-11T09:28:45"}]}]}'))
                ));
        }

        /** @var VestingBalance[] $assets */
        $assets = $this->sdk->getBalanceApi()->getAllVesting(new ChainObject('1.2.4'));
        foreach ($assets as $asset) {
            $this->assertEquals('1.2.4', $asset->getOwner());
        }
    }
}