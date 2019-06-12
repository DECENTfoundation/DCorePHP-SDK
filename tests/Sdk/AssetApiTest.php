<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetData;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\GetAssetData;
use DCorePHP\Net\Model\Request\GetAsset;
use DCorePHP\Net\Model\Request\GetAssets;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetRealSupply;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\ListAssets;
use DCorePHP\Net\Model\Request\LookupAssets;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class AssetApiTest extends DCoreSDKTest
{
    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGet(): void
    {

        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.0'));
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     */
    public function testGetAll(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $assets = $this->sdk->getAssetApi()->getAll([new ChainObject('1.3.0')]);

        $this->assertCount(1, $assets);

        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('1.3.0', $asset->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetRealSupply(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_real_supply",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetRealSupply::responseToModel(new BaseResponse('{"id":1,"result":{"account_balances":"5130346830557042","vesting_balances":"159841007700612","escrows":454184,"pools":"216690752000"}}'))
                ));
        }

        $realSupply = $this->sdk->getAssetApi()->getRealSupply();
        $this->assertInstanceOf(RealSupply::class, $realSupply);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListAllRelative(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"list_assets",["ALX",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    ListAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.44","symbol":"ALX","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":1000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.44"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.44"},{"id":"1.3.54","symbol":"ALXT","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":4,"asset_id":"1.3.54"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.54"},{"id":"1.3.50","symbol":"ASDF","precision":5,"issuer":"1.2.27","description":"desc","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.50"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.50"},{"id":"1.3.51","symbol":"ASDFG","precision":5,"issuer":"1.2.27","description":"desc","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.51"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.51"},{"id":"1.3.52","symbol":"ASDFGH","precision":1,"issuer":"1.2.27","description":"vnifdvnod","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.52"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.52"},{"id":"1.3.17","symbol":"AUD","precision":4,"issuer":"1.2.15","description":"Australian dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.17"},{"id":"1.3.4","symbol":"BGN","precision":4,"issuer":"1.2.15","description":"Bulgarian lev","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.4"},{"id":"1.3.61","symbol":"BIGSATOSHI","precision":12,"issuer":"1.2.27","description":"big satoshi token","options":{"max_supply":1000000,"core_exchange_rate":{"base":{"amount":259082,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.61"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.61"},{"id":"1.3.39","symbol":"BLEH","precision":0,"issuer":"1.2.27","description":"Disgusting token","options":{"max_supply":20000,"core_exchange_rate":{"base":{"amount":20000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.39"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.39"},{"id":"1.3.18","symbol":"BRL","precision":4,"issuer":"1.2.15","description":"Brazilian real","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.18"},{"id":"1.3.38","symbol":"BUZI","precision":0,"issuer":"1.2.27","description":"buzi token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.38"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.38"},{"id":"1.3.19","symbol":"CAD","precision":4,"issuer":"1.2.15","description":"Canadian dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.19"},{"id":"1.3.12","symbol":"CHF","precision":4,"issuer":"1.2.15","description":"Swiss franc","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.12"},{"id":"1.3.20","symbol":"CNY","precision":4,"issuer":"1.2.15","description":"Chinese yuan renminbi","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.20"},{"id":"1.3.5","symbol":"CZK","precision":4,"issuer":"1.2.15","description":"Czech koruna","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.5"},{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"},{"id":"1.3.6","symbol":"DKK","precision":4,"issuer":"1.2.15","description":"Danish krone","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.6"},{"id":"1.3.55","symbol":"DTO","precision":3,"issuer":"1.2.27","description":"Test asset","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.55"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.55"},{"id":"1.3.56","symbol":"DTOKENNN","precision":3,"issuer":"1.2.27","description":"Test asset","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.56"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.56"},{"id":"1.3.34","symbol":"DUS","precision":0,"issuer":"1.2.27","description":"Duskis custom token to buy a fokin content. Now begone Bitch","options":{"max_supply":80111,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.34"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.34"},{"id":"1.3.40","symbol":"DUSKIS","precision":0,"issuer":"1.2.27","description":"duski token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.40"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.40"},{"id":"1.3.2","symbol":"EUR","precision":4,"issuer":"1.2.15","description":"Euro","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.2"},{"id":"1.3.68","symbol":"EWETOKEN","precision":8,"issuer":"1.2.756","description":"such asset, much wow","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":100,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.68"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.68"},{"id":"1.3.7","symbol":"GBP","precision":4,"issuer":"1.2.15","description":"Pound sterling","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.7"},{"id":"1.3.21","symbol":"HKD","precision":4,"issuer":"1.2.15","description":"Hong Kong dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.21"},{"id":"1.3.46","symbol":"HMM","precision":0,"issuer":"1.2.15","description":"","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":"1000000000000","asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.46"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.46"},{"id":"1.3.14","symbol":"HRK","precision":4,"issuer":"1.2.15","description":"Croatian kuna","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.14"},{"id":"1.3.8","symbol":"HUF","precision":4,"issuer":"1.2.15","description":"Hungarian forint","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.8"},{"id":"1.3.22","symbol":"IDR","precision":4,"issuer":"1.2.15","description":"Indonesian rupiah","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.22"},{"id":"1.3.23","symbol":"ILS","precision":4,"issuer":"1.2.15","description":"Israeli shekel","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.23"},{"id":"1.3.24","symbol":"INR","precision":4,"issuer":"1.2.15","description":"Indian rupee","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.24"},{"id":"1.3.3","symbol":"JPY","precision":4,"issuer":"1.2.15","description":"Japanese yen","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.3"},{"id":"1.3.25","symbol":"KRW","precision":4,"issuer":"1.2.15","description":"South Korean won","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.25"},{"id":"1.3.70","symbol":"LIMITEDMAXSUPPLY","precision":2,"issuer":"1.2.11873","description":"Tohen with limited supply","options":{"max_supply":1,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.70"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.70"},{"id":"1.3.26","symbol":"MXN","precision":4,"issuer":"1.2.15","description":"Mexican peso","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.26"},{"id":"1.3.27","symbol":"MYR","precision":4,"issuer":"1.2.15","description":"Malaysian ringgit","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.27"},{"id":"1.3.13","symbol":"NOK","precision":4,"issuer":"1.2.15","description":"Norwegian krone","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.13"},{"id":"1.3.28","symbol":"NZD","precision":4,"issuer":"1.2.15","description":"New Zealand dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.28"},{"id":"1.3.29","symbol":"PHP","precision":4,"issuer":"1.2.15","description":"Philippine peso","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.29"},{"id":"1.3.37","symbol":"PIC","precision":0,"issuer":"1.2.27","description":"buzi token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.37"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.37"},{"id":"1.3.36","symbol":"PICKIN","precision":0,"issuer":"1.2.27","description":"Pickin token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":400000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.36"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.36"},{"id":"1.3.9","symbol":"PLN","precision":4,"issuer":"1.2.15","description":"Polish zloty","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.9"},{"id":"1.3.69","symbol":"PRECISETOKEN","precision":12,"issuer":"1.2.11873","description":"Tohen with high precision","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.69"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.69"},{"id":"1.3.58","symbol":"R1A","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"2100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.58"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.58"},{"id":"1.3.42","symbol":"RATATA","precision":0,"issuer":"1.2.27","description":"This token is so ra-ta-ta-ta-ta","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.42"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.42"},{"id":"1.3.10","symbol":"RON","precision":4,"issuer":"1.2.15","description":"Romanian leu","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.10"},{"id":"1.3.59","symbol":"RRI","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.59"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.59"},{"id":"1.3.57","symbol":"RRR","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"2100000000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.57"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.57"},{"id":"1.3.62","symbol":"RRRRR","precision":2,"issuer":"1.2.830","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.62"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.62"},{"id":"1.3.15","symbol":"RUB","precision":4,"issuer":"1.2.15","description":"Russian rouble","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.15"},{"id":"1.3.11","symbol":"SEK","precision":4,"issuer":"1.2.15","description":"Swedish krona","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.11"},{"id":"1.3.30","symbol":"SGD","precision":4,"issuer":"1.2.15","description":"Singapore dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.30"},{"id":"1.3.66","symbol":"T23456789012345T","precision":2,"issuer":"1.2.11873","description":"Test token with name length 16","options":{"max_supply":"10000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.66"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.66"},{"id":"1.3.64","symbol":"T4H","precision":2,"issuer":"1.2.11878","description":"Token 4 Hope","options":{"max_supply":10000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.64"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.64"},{"id":"1.3.45","symbol":"TESTCOIN","precision":8,"issuer":"1.2.86","description":"new desc","options":{"max_supply":"10000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.45"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.45"},{"id":"1.3.31","symbol":"THB","precision":4,"issuer":"1.2.15","description":"Thai baht","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.31"},{"id":"1.3.33","symbol":"TOKEN","precision":0,"issuer":"1.2.15","description":"desc","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.33"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.33"},{"id":"1.3.16","symbol":"TRY","precision":4,"issuer":"1.2.15","description":"Turkish lira","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.16"},{"id":"1.3.35","symbol":"TST","precision":0,"issuer":"1.2.27","description":"test token","options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":20000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.35"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.35"},{"id":"1.3.49","symbol":"UIA","precision":8,"issuer":"1.2.15","description":"desc","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.49"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.49"},{"id":"1.3.1","symbol":"USD","precision":4,"issuer":"1.2.15","description":"US dollar","monitored_asset_opts":{"feeds":[["1.2.85",["2018-06-15T09:58:20",{"core_exchange_rate":{"base":{"amount":4146,"asset_id":"1.3.1"},"quote":{"amount":10000,"asset_id":"1.3.0"}}}]]],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.1"},{"id":"1.3.41","symbol":"WUEY","precision":0,"issuer":"1.2.27","description":"nehehe token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"},{"id":"1.3.67","symbol":"XYZBLA","precision":8,"issuer":"1.2.756","description":"such asset, much wow","options":{"max_supply":1000000000,"core_exchange_rate":{"base":{"amount":100,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.67"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.67"},{"id":"1.3.32","symbol":"ZAR","precision":4,"issuer":"1.2.15","description":"South African rand","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.32"}]}'))
                ));
        }

        $assets = $this->sdk->getAssetApi()->listAllRelative('ALX');
        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->getByName('DCT');

        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAllByName(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"lookup_asset_symbols",[["DCT"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    LookupAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }
        $assets = $this->sdk->getAssetApi()->getAllByName(['DCT']);
        $asset = reset($assets);

        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAssetsData(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_objects",[["2.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssetData::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"2.3.0","current_supply":"5164464763045882","asset_pool":3501952,"core_pool":0}]}'))
                ));
        }

        /** @var AssetData[] $assets */
        $assets = $this->sdk->getAssetApi()->getAssetsData([new ChainObject('2.3.0')]);
        $asset = reset($assets);

        $this->assertEquals('2.3.0', $asset->getId()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testConvertFromDct(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $assetAmount = $this->sdk->getAssetApi()->convertFromDct(5, new ChainObject('1.3.0'));

        if ($this->websocketMock) {
            $this->assertEquals(5, $assetAmount->getAmount());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId());
        } else {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public function testConvertToDct(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.35"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.35","symbol":"SDK","precision":1,"issuer":"1.2.27","description":"hello new api","options":{"max_supply":2000000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.35"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.35"}]}'))
                ));
        }

        $assetAmount = $this->sdk->getAssetApi()->convertToDct(5, new ChainObject('1.3.35'));

        $this->assertEquals(3, $assetAmount->getAmount());
        $this->assertEquals('1.3.0', $assetAmount->getAssetId());
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testCreate(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[3,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[0][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[1]['operations'][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"ref_block_num":32718,"ref_block_prefix":"1625824373","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["' . $req->getParams()[1]['signatures'][0] . '"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1146830,"head_block_id":"00117fce751ce860345e3f2ac646afea245cf5b3","time":"2019-05-22T09:16:55","current_miner":"1.4.6","next_maintenance_time":"2019-05-23T00:00:00","last_budget_time":"2019-05-22T00:00:00","unspent_fee_budget":47747299,"mined_rewards":"225330000000","miner_budget_from_fees":73727239,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":6,"recently_missed_count":0,"current_aslot":1569596,"recent_slots_filled":"340261273212474181161592311795532300159","dynamic_flags":0,"last_irreversible_block_num":1146830}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"f272eac1ec922b468040314cfb4a5110fe2e9899","block_num":1146831,"trx_num":2,"trx":{"ref_block_num":32718,"ref_block_prefix":1625824373,"expiration":"2019-05-22T09:17:25","operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"SDK.1558516617T","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"is_exchangeable":true,"extensions":[]}]],"extensions":[],"signatures":["1f5936c6090fc490ee821907abf953e14261641b4006edecc8e42f258025f1934a4edb97bd04d5c5604f3c43b0605278c6fee42dcb4bfead294345f1904c074dfa"],"operation_results":[[1,"1.3.194"]]}}]]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.3.194","symbol":"SDK.1558516617T","precision":12,"issuer":"1.2.27","description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.194"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.194"}]}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK.' . time() . 'T';
        $this->sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        $asset = $this->sdk->getAssetApi()->getByName($symbol);
        $this->assertNotNull($asset);
        $this->assertEquals(AssetOptions::MAX_SHARE_SUPPLY, $asset->getOptions()->getMaxSupply());
        $this->assertTrue($asset->getOptions()->isExchangeable());
    }

    public function testCreateMonitored(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testUpdate(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(11))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[3,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[0][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[1]['operations'][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"ref_block_num":49322,"ref_block_prefix":"2390267883","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[0,"get_required_fees",[[[36,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.223","new_description":"hello api from PHP update from PHP","new_issuer":null,"max_supply":"3659888788728445","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.223"}},"is_exchangeable":true,"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(10)->toJson() === '{"jsonrpc":"2.0","id":10,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[36,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.223","new_description":"hello api from PHP update from PHP","new_issuer":null,"max_supply":"3659888788728445","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.223"}},"is_exchangeable":true,"extensions":[]}]],"ref_block_num":49323,"ref_block_prefix":"688769403","expiration":"2019-05-23T10:38:59","signatures":["1f193d39b23cc0a3ac89e5223118f1d4fb39a6dc4cd43142ad66eae40b296dfa6243382ef0a24eb06ce136ae0b1def0b4eba7f0836e52360b90fb0f62dc2d9801c"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(11)->toJson() === '{"jsonrpc":"2.0","id":11,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1163434,"head_block_id":"0011c0aaeb97788e2645d5c27348c922e030541d","time":"2019-05-23T10:38:20","current_miner":"1.4.2","next_maintenance_time":"2019-05-24T00:00:00","last_budget_time":"2019-05-23T00:00:00","unspent_fee_budget":104122498,"mined_rewards":"257594000000","miner_budget_from_fees":174369078,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":6,"recently_missed_count":4,"current_aslot":1587850,"recent_slots_filled":"319009526681540080868786003103939813374","dynamic_flags":0,"last_irreversible_block_num":1163434}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"425366cb516dd5f514ca18353ea592770480723c","block_num":1163435,"trx_num":0,"trx":{"ref_block_num":49322,"ref_block_prefix":2390267883,"expiration":"2019-05-23T10:38:59","operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"SDK.1558607900T","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"is_exchangeable":true,"extensions":[]}]],"extensions":[],"signatures":["202a07653939961b84dd0d3e09ac530e9e64afc8333f574820fd8c19869572654d6860657e4fd6fa4ec0016d4a20055f4818a04aedb41623bbcb27ce02bb4ff3f1"],"operation_results":[[1,"1.3.223"]]}}]]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.3.223","symbol":"SDK.1558607900T","precision":12,"issuer":"1.2.27","description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.223"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.223"}]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":6,"result":[{"id":"1.3.223","symbol":"SDK.1558607900T","precision":12,"issuer":"1.2.27","description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.223"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.223"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":7,"result":{"id":"2.1.0","head_block_number":1163435,"head_block_id":"0011c0ab7bc90d2921d08bae2ecbf6a244986c7f","time":"2019-05-23T10:38:25","current_miner":"1.4.7","next_maintenance_time":"2019-05-24T00:00:00","last_budget_time":"2019-05-23T00:00:00","unspent_fee_budget":104112408,"mined_rewards":"257631000000","miner_budget_from_fees":174369078,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":6,"recently_missed_count":3,"current_aslot":1587851,"recent_slots_filled":"297736686442141698274197398776111415293","dynamic_flags":0,"last_irreversible_block_num":1163435}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":8,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":9,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"331ee032fd12bc723377caa1588f2673a0fafc2e","block_num":1163436,"trx_num":0,"trx":{"ref_block_num":49323,"ref_block_prefix":688769403,"expiration":"2019-05-23T10:38:59","operations":[[36,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.223","new_description":"hello api from PHP update from PHP","max_supply":"3659888788728445","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.223"}},"is_exchangeable":true,"extensions":[]}]],"extensions":[],"signatures":["1f193d39b23cc0a3ac89e5223118f1d4fb39a6dc4cd43142ad66eae40b296dfa6243382ef0a24eb06ce136ae0b1def0b4eba7f0836e52360b90fb0f62dc2d9801c"],"operation_results":[[0,{}]]}}]]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":11,"result":[{"id":"1.3.125","symbol":"SDK.1557410326T","precision":12,"issuer":"1.2.27","description":"hello api from PHP update from PHP","options":{"max_supply":"3659888788728445","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.125"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.125"}]}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK.' . time() . 'T';
        $this->sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        $old = $this->sdk->getAssetApi()->getByName($symbol);

        $this->sdk->getAssetApi()->update(
            $credentials,
            $symbol,
            new ExchangeRate((new AssetAmount())->setAmount(1), (new AssetAmount())->setAmount(2)->setAssetId($old->getId())),
            $old->getDescription() . ' update from PHP',
            true,
            gmp_div($old->getOptions()->getMaxSupply(), 2)
            );

        $new = $this->sdk->getAssetApi()->getByName($symbol);

        $this->assertEquals($old->getDescription() . ' update from PHP', $new->getDescription());
        $this->assertTrue($new->getOptions()->isExchangeable());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws \Exception
     */
    public function testUpdateAdvanced(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(10))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_required_fees",[[[3,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[0][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"'.$req->getParams()[1]['operations'][0][1]['symbol'].'","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"extensions":[]}]],"ref_block_num":49384,"ref_block_prefix":"2638953794","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_required_fees",[[[40,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.225","new_precision":6,"set_fixed_max_supply":false}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[40,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.225","new_precision":6,"set_fixed_max_supply":false}]],"ref_block_num":49385,"ref_block_prefix":"4151414408","expiration":"'.$req->getParams()[1]['expiration'].'","signatures":["'.$req->getParams()[1]['signatures'][0].'"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(10)->toJson() === '{"jsonrpc":"2.0","id":10,"method":"call","params":[0,"lookup_asset_symbols",[["'.$req->getParams()[0][0].'"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.1.0","head_block_number":1163496,"head_block_id":"0011c0e8423d4b9d230039f1bae8fac19b1ce2c4","time":"2019-05-23T10:43:55","current_miner":"1.4.3","next_maintenance_time":"2019-05-24T00:00:00","last_budget_time":"2019-05-23T00:00:00","unspent_fee_budget":103496918,"mined_rewards":"259888000000","miner_budget_from_fees":174369078,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":6,"recently_missed_count":0,"current_aslot":1587917,"recent_slots_filled":"340277093493821334935883999771604285439","dynamic_flags":0,"last_irreversible_block_num":1163496}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":2,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":3,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"0ae2acdb43a83dad7d652ce5fdcc3e97771c2498","block_num":1163497,"trx_num":0,"trx":{"ref_block_num":49384,"ref_block_prefix":2638953794,"expiration":"2019-05-23T10:44:28","operations":[[3,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","symbol":"SDK.1558608236T","precision":12,"description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.1"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"is_exchangeable":true,"extensions":[]}]],"extensions":[],"signatures":["1f26666c68ae970f244962296a8592913e6e771fce8f10af17e0205e844eb7502d412a09ed1caad8a2dd17c61dd61dfb54e7a4afa6dd7bf7bf1b725c16b803c2ee"],"operation_results":[[1,"1.3.225"]]}}]]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.3.225","symbol":"SDK.1558608236T","precision":12,"issuer":"1.2.27","description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.225"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.225"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":6,"result":{"id":"2.1.0","head_block_number":1163497,"head_block_id":"0011c0e9888e71f7091524db0b2b05e18c2e6372","time":"2019-05-23T10:44:00","current_miner":"1.4.2","next_maintenance_time":"2019-05-24T00:00:00","last_budget_time":"2019-05-23T00:00:00","unspent_fee_budget":103486828,"mined_rewards":"259925000000","miner_budget_from_fees":174369078,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":6,"recently_missed_count":0,"current_aslot":1587918,"recent_slots_filled":"340271820066704206408393392111440359423","dynamic_flags":0,"last_irreversible_block_num":1163497}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":7,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":8,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"499b7dbebc6cd5373e5126610724db4cd94be340","block_num":1163498,"trx_num":0,"trx":{"ref_block_num":49385,"ref_block_prefix":4151414408,"expiration":"2019-05-23T10:44:36","operations":[[40,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_update":"1.3.225","new_precision":6,"set_fixed_max_supply":false,"extensions":[]}]],"extensions":[],"signatures":["1f13700836d5e795befb20c1f416dd4de701b199ab1533a6346b21b546caac9312503ebf6bd2684f5e18b28d1e38742a9f66fbb19d9f0637f5016234e35fcc22d3"],"operation_results":[[0,{}]]}}]]}')),
                    LookupAssets::responseToModel(new BaseResponse('{"id":10,"result":[{"id":"1.3.225","symbol":"SDK.1558608236T","precision":6,"issuer":"1.2.27","description":"hello api from PHP","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.225"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.225"}]}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $symbol = 'SDK.' . time() . 'T';
        $this->sdk->getAssetApi()->create($credentials, $symbol, 12, 'hello api from PHP');

        $this->sdk->getAssetApi()->updateAdvanced(
            $credentials,
            $symbol,
            6,
            false
        );

        $newAsset = $this->sdk->getAssetApi()->getByName($symbol);
        $this->assertEquals(6, $newAsset->getPrecision());
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testIssue(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(8))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"get_required_fees",[[[4,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_issue":{"amount":200,"asset_id":"1.3.41"},"issue_to_account":"1.2.27","memo":null}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[4,{"fee":{"amount":500488,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_issue":{"amount":200,"asset_id":"1.3.41"},"issue_to_account":"1.2.27","memo":null}]],"ref_block_num":39082,"ref_block_prefix":"3321974892","expiration":"2019-05-14T10:51:30","signatures":["2005dd09efdddbea7c66f408ef999a348142f8f94642f0ed621ef79ea377515b625903d775282f09a41e0ce263fb826fdf7edff9acc83274051b712685cc7904b5"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"2.3.41","current_supply":2600,"asset_pool":0,"core_pool":750000}]}')),
                    GetAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":4,"result":{"id":"2.1.0","head_block_number":1022122,"head_block_id":"000f98aa6c5001c646ce4e9b86c5692265265e35","time":"2019-05-14T10:51:00","current_miner":"1.4.1","next_maintenance_time":"2019-05-15T00:00:00","last_budget_time":"2019-05-14T00:00:00","unspent_fee_budget":36776529,"mined_rewards":"262663000000","miner_budget_from_fees":62411018,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":4,"current_aslot":1432509,"recent_slots_filled":"340261516602618293375663884994978215934","dynamic_flags":0,"last_irreversible_block_num":1022122}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":6,"result":[{"amount":500488,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"f7e82c3bd54571eda3c2c57a863ecd0f88032110","block_num":1163552,"trx_num":0,"trx":{"ref_block_num":49438,"ref_block_prefix":3599360647,"expiration":"2019-05-23T10:49:20","operations":[[4,{"fee":{"amount":500488,"asset_id":"1.3.0"},"issuer":"1.2.27","asset_to_issue":{"amount":200,"asset_id":"1.3.41"},"issue_to_account":"1.2.27","extensions":[]}]],"extensions":[],"signatures":["1f284884fee1ace07936b5338f79c3d0a7ecddfad622908180e761d0b9c5cf211249f982a12c48293dbbd9a8f36b5c5d16ddfc8bf683c51b8bf54bf208b2e5b574"],"operation_results":[[0,{}]]}}]]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":8,"result":[{"id":"2.3.41","current_supply":2800,"asset_pool":0,"core_pool":750000}]}'))
                ));
        }

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.41'));
        /** @var AssetData[] $oldData */
        $oldData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $this->sdk->getAssetApi()->issue($credentials, new ChainObject('1.3.41'), 200);

        /** @var AssetData[] $newData */
        $newData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);

        $this->assertEquals(200, $newData->getCurrentSupply() - $oldData->getCurrentSupply());
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws \Exception
     */
    public function testFund(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(8))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"get_required_fees",[[[33,{"fee":{"amount":0,"asset_id":"1.3.0"},"from_account":"1.2.27","uia_asset":{"amount":0,"asset_id":"1.3.41"},"dct_asset":{"amount":1000,"asset_id":"1.3.0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[33,{"fee":{"amount":500000,"asset_id":"1.3.0"},"from_account":"1.2.27","uia_asset":{"amount":0,"asset_id":"1.3.41"},"dct_asset":{"amount":1000,"asset_id":"1.3.0"}}]],"ref_block_num":39203,"ref_block_prefix":"2246660901","expiration":"2019-05-14T11:02:32","signatures":["1f1f97f0015d827061d734cd64180564b51580cf6304d4cd61948dc2e9c343d36d30629574f7c1e73036a11ae5ea2f953b17fa711c650725284cf03ccb6c52e8d6"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"2.3.41","current_supply":2800,"asset_pool":0,"core_pool":851000}]}')),
                    GetAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":4,"result":{"id":"2.1.0","head_block_number":1022243,"head_block_id":"000f99232553e98556660c425cfb6dbf0aaf9f38","time":"2019-05-14T11:01:55","current_miner":"1.4.2","next_maintenance_time":"2019-05-15T00:00:00","last_budget_time":"2019-05-14T00:00:00","unspent_fee_budget":36339598,"mined_rewards":"267140000000","miner_budget_from_fees":62411018,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":1,"current_aslot":1432640,"recent_slots_filled":"340271962044499887285745144128635666423","dynamic_flags":0,"last_irreversible_block_num":1022243}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":6,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"b6076f6e057fae56479d6c57a7c227dbac8c66a7","block_num":1163569,"trx_num":0,"trx":{"ref_block_num":49456,"ref_block_prefix":239774468,"expiration":"2019-05-23T10:51:08","operations":[[33,{"fee":{"amount":500000,"asset_id":"1.3.0"},"from_account":"1.2.27","uia_asset":{"amount":0,"asset_id":"1.3.41"},"dct_asset":{"amount":1000,"asset_id":"1.3.0"},"extensions":[]}]],"extensions":[],"signatures":["1f251bc4aaa962a5cdba8103e0e7a22a39701e5b2d79086904ed80b1db1c5e7e226efe2a6baa29da39f64de3e72563a8ee55084aeeef6acc7492cab1831d7075f7"],"operation_results":[[0,{}]]}}]]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":8,"result":[{"id":"2.3.41","current_supply":2800,"asset_pool":0,"core_pool":852000}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.41'));
        /** @var AssetData[] $oldData */
        $oldData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getAssetApi()->fund($credentials, new ChainObject('1.3.41'), 0, 1000);

        /** @var AssetData[] $newData */
        $newData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(1000, $newData->getCorePool() - $oldData->getCorePool());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws \Exception
     */
    public function testClaim(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(8))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.35"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_objects",[["2.3.35"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_assets",[["1.3.35"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"get_required_fees",[[[35,{"fee":{"amount":0,"asset_id":"1.3.0"},"issuer":"1.2.27","uia_asset":{"amount":200,"asset_id":"1.3.35"},"dct_asset":{"amount":0,"asset_id":"1.3.0"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[35,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","uia_asset":{"amount":200,"asset_id":"1.3.35"},"dct_asset":{"amount":0,"asset_id":"1.3.0"}}]],"ref_block_num":39810,"ref_block_prefix":"576255062","expiration":"2019-05-14T11:57:52","signatures":["206ce155f5cc915ee15484e6004ad4699d51a99a2a60bf860e68674fcfb814e828111445371d6b69fddc54e2cb883c76368036a528b5e03807f979aa1721392cb0"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_objects",[["2.3.35"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.35","symbol":"SDK","precision":1,"issuer":"1.2.27","description":"hello new api","options":{"max_supply":2000000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.35"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.35"}]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"2.3.35","current_supply":100100,"asset_pool":100100,"core_pool":250100100}]}')),
                    GetAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.35","symbol":"SDK","precision":1,"issuer":"1.2.27","description":"hello new api","options":{"max_supply":2000000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":2,"asset_id":"1.3.35"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.35"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":4,"result":{"id":"2.1.0","head_block_number":1022850,"head_block_id":"000f9b8256f45822f2e4d62325d06762959df2bd","time":"2019-05-14T11:57:20","current_miner":"1.4.5","next_maintenance_time":"2019-05-15T00:00:00","last_budget_time":"2019-05-14T00:00:00","unspent_fee_budget":34147721,"mined_rewards":"289599000000","miner_budget_from_fees":62411018,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":0,"recently_missed_count":0,"current_aslot":1433305,"recent_slots_filled":"339617671634796443105418527899278766079","dynamic_flags":0,"last_irreversible_block_num":1022850}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":6,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"97dc2909cb1ef3c7371dfea94d4f94bbf0d4c313","block_num":1163586,"trx_num":0,"trx":{"ref_block_num":49473,"ref_block_prefix":2228660835,"expiration":"2019-05-23T10:52:30","operations":[[35,{"fee":{"amount":500000,"asset_id":"1.3.0"},"issuer":"1.2.27","uia_asset":{"amount":200,"asset_id":"1.3.35"},"dct_asset":{"amount":0,"asset_id":"1.3.0"},"extensions":[]}]],"extensions":[],"signatures":["20671b9e5d092eed22b8dbeaf5497fc369a1ae507a47505646a1b2cd33d795501029bf55920c930685e2f18fe82f395e2ad85bda92e5bc482b1ddffa3666cce034"],"operation_results":[[0,{}]]}}]]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":8,"result":[{"id":"2.3.35","current_supply":100100,"asset_pool":99900,"core_pool":250100100}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.35'));
        /** @var AssetData[] $oldData */
        $oldData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getAssetApi()->claim($credentials, new ChainObject('1.3.35'), 200, 0);

        /** @var AssetData[] $newData */
        $newData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(200, $oldData->getAssetPool() - $newData->getAssetPool());
    }

    public function testReserve(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(8))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[0,"get_assets",[["1.3.41"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[0,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[0,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[0,"get_required_fees",[[[34,{"fee":{"amount":0,"asset_id":"1.3.0"},"payer":"1.2.27","amount_to_reserve":{"amount":100,"asset_id":"1.3.41"}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[2,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[34,{"fee":{"amount":500000,"asset_id":"1.3.0"},"payer":"1.2.27","amount_to_reserve":{"amount":100,"asset_id":"1.3.41"}}]],"ref_block_num":55212,"ref_block_prefix":"3298952665","expiration":"2019-05-15T11:29:42","signatures":["1f3c9338ef200f06eb56f543cea5012100526962bacf0a812a58f3847f1def19093d79a03b3041d4031edf0a3c8b0430d1970a8ffcb22014db4573de311095aafb"]}]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[0,"get_objects",[["2.3.41"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetAssets::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":2,"result":[{"id":"2.3.41","current_supply":3000,"asset_pool":0,"core_pool":855000}]}')),
                    GetAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.41","symbol":"SDK.6T","precision":6,"issuer":"1.2.27","description":"hello api","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"}]}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":4,"result":{"id":"2.1.0","head_block_number":1038252,"head_block_id":"000fd7acd905a2c4527559406aad5029cc1df1bb","time":"2019-05-15T11:29:05","current_miner":"1.4.6","next_maintenance_time":"2019-05-16T00:00:00","last_budget_time":"2019-05-15T00:00:00","unspent_fee_budget":19368934,"mined_rewards":"278203000000","miner_budget_from_fees":34286630,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":3,"recently_missed_count":0,"current_aslot":1450243,"recent_slots_filled":"340199209001939738646574934617764003775","dynamic_flags":0,"last_irreversible_block_num":1038252}}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":5,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":6,"result":[{"amount":500000,"asset_id":"1.3.0"}]}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"1a633553e9ad2fe0005663f86f2674dac67d1bea","block_num":1163601,"trx_num":0,"trx":{"ref_block_num":49487,"ref_block_prefix":3331952730,"expiration":"2019-05-23T10:53:56","operations":[[34,{"fee":{"amount":500000,"asset_id":"1.3.0"},"payer":"1.2.27","amount_to_reserve":{"amount":100,"asset_id":"1.3.41"},"extensions":[]}]],"extensions":[],"signatures":["2061e599c44834cfdb5efc43ee604ab0aade6f24eb5564bf3bd09eb6e8b0104e1f1baa666deb29ddd5be00c2004d365dc2ec5f544c4fdfa80c850e21b85094dc4d"],"operation_results":[[0,{}]]}}]]}')),
                    GetAssetData::responseToModel(new BaseResponse('{"id":8,"result":[{"id":"2.3.41","current_supply":2900,"asset_pool":0,"core_pool":855000}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.41'));
        /** @var AssetData[] $oldData */
        $oldData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $oldData = reset($oldData);

        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getAssetApi()->reserve($credentials, new ChainObject('1.3.41'), 100);

        /** @var AssetData[] $newData */
        $newData = $this->sdk->getAssetApi()->getAssetsData([$asset->getDataId()]);
        $newData = reset($newData);
        $this->assertEquals(100, $oldData->getCurrentSupply() - $newData->getCurrentSupply());
    }
}
