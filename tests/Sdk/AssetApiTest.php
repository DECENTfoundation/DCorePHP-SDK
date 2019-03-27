<?php


namespace DCorePHPTests\Sdk;

use DCorePHP\DCoreApi;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\RealSupply;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAsset;
use DCorePHP\Net\Model\Request\GetAssets;
use DCorePHP\Net\Model\Request\GetRealSupply;
use DCorePHP\Net\Model\Request\ListAssets;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\PriceToDct;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_assets",[["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAsset::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $asset = $this->sdk->getAssetApi()->get(new ChainObject('1.3.0'));
        $this->assertEquals('DCT', $asset->getSymbol());
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_assets",[["1.3.0"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"}]}'))
                ));
        }

        $assets = $this->sdk->getAssetApi()->getAll([new ChainObject('1.3.0')]);

        $this->assertCount(1, $assets);

        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('1.3.0', $asset->getId());
    }

    public function testGetRealSupply(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_real_supply",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetRealSupply::responseToModel(new BaseResponse('{"id":3,"result":{"account_balances":"5130346830557042","vesting_balances":"159841007700612","escrows":454184,"pools":"216690752000"}}'))
                ));
        }

        $realSupply = $this->sdk->getAssetApi()->getRealSupply();
        $this->assertInstanceOf(RealSupply::class, $realSupply);
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
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_assets",["ALX",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListAssets::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.44","symbol":"ALX","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":1000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.44"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.44"},{"id":"1.3.54","symbol":"ALXT","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":4,"asset_id":"1.3.54"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.54"},{"id":"1.3.50","symbol":"ASDF","precision":5,"issuer":"1.2.27","description":"desc","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.50"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.50"},{"id":"1.3.51","symbol":"ASDFG","precision":5,"issuer":"1.2.27","description":"desc","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.51"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.51"},{"id":"1.3.52","symbol":"ASDFGH","precision":1,"issuer":"1.2.27","description":"vnifdvnod","options":{"max_supply":100,"core_exchange_rate":{"base":{"amount":100000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.52"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.52"},{"id":"1.3.17","symbol":"AUD","precision":4,"issuer":"1.2.15","description":"Australian dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.17"},{"id":"1.3.4","symbol":"BGN","precision":4,"issuer":"1.2.15","description":"Bulgarian lev","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.4"},{"id":"1.3.61","symbol":"BIGSATOSHI","precision":12,"issuer":"1.2.27","description":"big satoshi token","options":{"max_supply":1000000,"core_exchange_rate":{"base":{"amount":259082,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.61"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.61"},{"id":"1.3.39","symbol":"BLEH","precision":0,"issuer":"1.2.27","description":"Disgusting token","options":{"max_supply":20000,"core_exchange_rate":{"base":{"amount":20000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.39"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.39"},{"id":"1.3.18","symbol":"BRL","precision":4,"issuer":"1.2.15","description":"Brazilian real","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.18"},{"id":"1.3.38","symbol":"BUZI","precision":0,"issuer":"1.2.27","description":"buzi token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.38"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.38"},{"id":"1.3.19","symbol":"CAD","precision":4,"issuer":"1.2.15","description":"Canadian dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.19"},{"id":"1.3.12","symbol":"CHF","precision":4,"issuer":"1.2.15","description":"Swiss franc","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.12"},{"id":"1.3.20","symbol":"CNY","precision":4,"issuer":"1.2.15","description":"Chinese yuan renminbi","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.20"},{"id":"1.3.5","symbol":"CZK","precision":4,"issuer":"1.2.15","description":"Czech koruna","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.5"},{"id":"1.3.0","symbol":"DCT","precision":8,"issuer":"1.2.1","description":"","options":{"max_supply":"7319777577456900","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.0"},{"id":"1.3.6","symbol":"DKK","precision":4,"issuer":"1.2.15","description":"Danish krone","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.6"},{"id":"1.3.55","symbol":"DTO","precision":3,"issuer":"1.2.27","description":"Test asset","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.55"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.55"},{"id":"1.3.56","symbol":"DTOKENNN","precision":3,"issuer":"1.2.27","description":"Test asset","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.56"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.56"},{"id":"1.3.34","symbol":"DUS","precision":0,"issuer":"1.2.27","description":"Duskis custom token to buy a fokin content. Now begone Bitch","options":{"max_supply":80111,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.34"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.34"},{"id":"1.3.40","symbol":"DUSKIS","precision":0,"issuer":"1.2.27","description":"duski token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.40"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.40"},{"id":"1.3.2","symbol":"EUR","precision":4,"issuer":"1.2.15","description":"Euro","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.2"},{"id":"1.3.68","symbol":"EWETOKEN","precision":8,"issuer":"1.2.756","description":"such asset, much wow","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":100,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.68"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.68"},{"id":"1.3.7","symbol":"GBP","precision":4,"issuer":"1.2.15","description":"Pound sterling","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.7"},{"id":"1.3.21","symbol":"HKD","precision":4,"issuer":"1.2.15","description":"Hong Kong dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.21"},{"id":"1.3.46","symbol":"HMM","precision":0,"issuer":"1.2.15","description":"","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":"1000000000000","asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.46"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.46"},{"id":"1.3.14","symbol":"HRK","precision":4,"issuer":"1.2.15","description":"Croatian kuna","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.14"},{"id":"1.3.8","symbol":"HUF","precision":4,"issuer":"1.2.15","description":"Hungarian forint","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.8"},{"id":"1.3.22","symbol":"IDR","precision":4,"issuer":"1.2.15","description":"Indonesian rupiah","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.22"},{"id":"1.3.23","symbol":"ILS","precision":4,"issuer":"1.2.15","description":"Israeli shekel","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.23"},{"id":"1.3.24","symbol":"INR","precision":4,"issuer":"1.2.15","description":"Indian rupee","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.24"},{"id":"1.3.3","symbol":"JPY","precision":4,"issuer":"1.2.15","description":"Japanese yen","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.3"},{"id":"1.3.25","symbol":"KRW","precision":4,"issuer":"1.2.15","description":"South Korean won","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.25"},{"id":"1.3.70","symbol":"LIMITEDMAXSUPPLY","precision":2,"issuer":"1.2.11873","description":"Tohen with limited supply","options":{"max_supply":1,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.70"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.70"},{"id":"1.3.26","symbol":"MXN","precision":4,"issuer":"1.2.15","description":"Mexican peso","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.26"},{"id":"1.3.27","symbol":"MYR","precision":4,"issuer":"1.2.15","description":"Malaysian ringgit","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.27"},{"id":"1.3.13","symbol":"NOK","precision":4,"issuer":"1.2.15","description":"Norwegian krone","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.13"},{"id":"1.3.28","symbol":"NZD","precision":4,"issuer":"1.2.15","description":"New Zealand dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.28"},{"id":"1.3.29","symbol":"PHP","precision":4,"issuer":"1.2.15","description":"Philippine peso","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.29"},{"id":"1.3.37","symbol":"PIC","precision":0,"issuer":"1.2.27","description":"buzi token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.37"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.37"},{"id":"1.3.36","symbol":"PICKIN","precision":0,"issuer":"1.2.27","description":"Pickin token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":400000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.36"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.36"},{"id":"1.3.9","symbol":"PLN","precision":4,"issuer":"1.2.15","description":"Polish zloty","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.9"},{"id":"1.3.69","symbol":"PRECISETOKEN","precision":12,"issuer":"1.2.11873","description":"Tohen with high precision","options":{"max_supply":"7319777577456890","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.69"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.69"},{"id":"1.3.58","symbol":"R1A","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"2100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.58"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.58"},{"id":"1.3.42","symbol":"RATATA","precision":0,"issuer":"1.2.27","description":"This token is so ra-ta-ta-ta-ta","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.42"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.42"},{"id":"1.3.10","symbol":"RON","precision":4,"issuer":"1.2.15","description":"Romanian leu","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.10"},{"id":"1.3.59","symbol":"RRI","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"1000000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.59"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.59"},{"id":"1.3.57","symbol":"RRR","precision":6,"issuer":"1.2.15","description":"","options":{"max_supply":"2100000000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.57"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.57"},{"id":"1.3.62","symbol":"RRRRR","precision":2,"issuer":"1.2.830","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.62"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.62"},{"id":"1.3.15","symbol":"RUB","precision":4,"issuer":"1.2.15","description":"Russian rouble","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.15"},{"id":"1.3.11","symbol":"SEK","precision":4,"issuer":"1.2.15","description":"Swedish krona","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.11"},{"id":"1.3.30","symbol":"SGD","precision":4,"issuer":"1.2.15","description":"Singapore dollar","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.30"},{"id":"1.3.66","symbol":"T23456789012345T","precision":2,"issuer":"1.2.11873","description":"Test token with name length 16","options":{"max_supply":"10000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.66"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.66"},{"id":"1.3.64","symbol":"T4H","precision":2,"issuer":"1.2.11878","description":"Token 4 Hope","options":{"max_supply":10000000,"core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.64"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.64"},{"id":"1.3.45","symbol":"TESTCOIN","precision":8,"issuer":"1.2.86","description":"new desc","options":{"max_supply":"10000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.45"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.45"},{"id":"1.3.31","symbol":"THB","precision":4,"issuer":"1.2.15","description":"Thai baht","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.31"},{"id":"1.3.33","symbol":"TOKEN","precision":0,"issuer":"1.2.15","description":"desc","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.33"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":true}]]},"dynamic_asset_data_id":"2.3.33"},{"id":"1.3.16","symbol":"TRY","precision":4,"issuer":"1.2.15","description":"Turkish lira","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.16"},{"id":"1.3.35","symbol":"TST","precision":0,"issuer":"1.2.27","description":"test token","options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":20000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.35"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.35"},{"id":"1.3.49","symbol":"UIA","precision":8,"issuer":"1.2.15","description":"desc","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.49"}},"is_exchangeable":false,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.49"},{"id":"1.3.1","symbol":"USD","precision":4,"issuer":"1.2.15","description":"US dollar","monitored_asset_opts":{"feeds":[["1.2.85",["2018-06-15T09:58:20",{"core_exchange_rate":{"base":{"amount":4146,"asset_id":"1.3.1"},"quote":{"amount":10000,"asset_id":"1.3.0"}}}]]],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.1"},{"id":"1.3.41","symbol":"WUEY","precision":0,"issuer":"1.2.27","description":"nehehe token","options":{"max_supply":10000,"core_exchange_rate":{"base":{"amount":200000000,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.41"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.41"},{"id":"1.3.67","symbol":"XYZBLA","precision":8,"issuer":"1.2.756","description":"such asset, much wow","options":{"max_supply":1000000000,"core_exchange_rate":{"base":{"amount":100,"asset_id":"1.3.0"},"quote":{"amount":1,"asset_id":"1.3.67"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.67"},{"id":"1.3.32","symbol":"ZAR","precision":4,"issuer":"1.2.15","description":"South African rand","monitored_asset_opts":{"feeds":[],"current_feed":{"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}}},"current_feed_publication_time":"2019-02-24T12:57:35","feed_lifetime_sec":86400,"minimum_feeds":1},"options":{"max_supply":0,"core_exchange_rate":{"base":{"amount":0,"asset_id":"1.3.0"},"quote":{"amount":0,"asset_id":"1.3.0"}},"is_exchangeable":true,"extensions":[]},"dynamic_asset_data_id":"2.3.32"}]}'))
                ));
        }

        $assets = $this->sdk->getAssetApi()->listAllRelative('ALX');
        $asset = reset($assets);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    public function testGetByName(): void
    {
        // No Data
        $asset = $this->sdk->getAssetApi()->getByName('DCT');
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testGetAllByName(): void
    {
        // No data
        $assets = $this->sdk->getAssetApi()->getAllByName(['DCT']);
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testConvertFromDct(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_assets",[["1.3.54"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAsset::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.54","symbol":"ALXT","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":4,"asset_id":"1.3.54"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.54"}]}'))
                ));
        }

        $assetAmount = $this->sdk->getAssetApi()->convertFromDct(5, new ChainObject('1.3.54'));

        if ($this->websocketMock) {
            $this->assertEquals(20, $assetAmount->getAmount());
            $this->assertEquals('1.3.54', $assetAmount->getAssetId());
        } else {
            $this->expectNotToPerformAssertions();
        }
    }

    public function testConvertToDct(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_assets",[["1.3.54"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAsset::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.3.54","symbol":"ALXT","precision":8,"issuer":"1.2.15","description":"","options":{"max_supply":"100000000000000","core_exchange_rate":{"base":{"amount":1,"asset_id":"1.3.0"},"quote":{"amount":4,"asset_id":"1.3.54"}},"is_exchangeable":true,"extensions":[[1,{"is_fixed_max_supply":false}]]},"dynamic_asset_data_id":"2.3.54"}]}'))
                ));
        }

        $assetAmount = $this->sdk->getAssetApi()->convertToDct(5, new ChainObject('1.3.54'));

        if ($this->websocketMock) {
            $this->assertEquals(2, $assetAmount->getAmount());
            $this->assertEquals('1.3.0', $assetAmount->getAssetId());
        } else {
            $this->expectNotToPerformAssertions();
        }
    }

    public function testGetMonitoredAssetData(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateMonitoredAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testUpdateMonitoredAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateUserIssuedAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testIssueAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testUpdateUserIssuedAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testFundAssetPools(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testReserveAsset(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testClaimFees(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testPublishAssetFeed(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}