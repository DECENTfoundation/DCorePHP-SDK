<?php


namespace DCorePHPTests\Sdk;


use DCorePHP\DCoreApi;
use DCorePHP\Model\Content\Seeder;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetSeederAbstract;
use DCorePHP\Net\Model\Request\ListSeedersByUpload;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class SeederApiTest extends DCoreSDKTest
{
    public function testGetSeeder(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_seeder",["1.2.16"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetSeederAbstract::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.14.0","seeder":"1.2.16","free_space":1022,"price":{"amount":1,"asset_id":"1.3.0"},"expiration":"2018-09-14T02:10:05","pubKey":{"s":"108509137992084552766842257584642929445130808368600055288928130756106214148863141200188299504000640159872636359336882924163527129138321742300857400054934."},"ipfs_ID":"Qmd1WE8qhNDhwbZwRn4J6UJwAoBCgxX7iLHM856pkvDFdJ","stats":"2.16.0","rating":0,"region_code":""}}'))
                ));
        }

        $seeder = $this->sdk->getSeederApi()->get(new ChainObject('1.2.16'));

        $this->assertInstanceOf(Seeder::class, $seeder);
        $this->assertEquals(1022, $seeder->getFreeSpace());
        $this->assertEquals('Qmd1WE8qhNDhwbZwRn4J6UJwAoBCgxX7iLHM856pkvDFdJ', $seeder->getIpfsId());
    }

    public function testListSeedersByPrice(): void
    {
        // TODO: No data
        $seeders = $this->sdk->getSeederApi()->listByPrice();
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testListSeedersByUpload(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_seeders_by_upload",[100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListSeedersByUpload::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.14.0","seeder":"1.2.16","free_space":1022,"price":{"amount":1,"asset_id":"1.3.0"},"expiration":"2018-09-14T02:10:05","pubKey":{"s":"108509137992084552766842257584642929445130808368600055288928130756106214148863141200188299504000640159872636359336882924163527129138321742300857400054934."},"ipfs_ID":"Qmd1WE8qhNDhwbZwRn4J6UJwAoBCgxX7iLHM856pkvDFdJ","stats":"2.16.0","rating":0,"region_code":""},{"id":"2.14.1","seeder":"1.2.90","free_space":1018,"price":{"amount":1,"asset_id":"1.3.0"},"expiration":"2018-07-31T04:16:25","pubKey":{"s":"8490319717792401336022711903211688203776011372958543603431635388400597658916629399144065413122027733188246528373794568719741969521921155180202961044843254."},"ipfs_ID":"QmVotciYd21wM5fbQzCA4S9nG5hqumkcbfjvHJtLnsAWoF","stats":"2.16.1","rating":0,"region_code":""},{"id":"2.14.2","seeder":"1.2.91","free_space":1022,"price":{"amount":1,"asset_id":"1.3.0"},"expiration":"2018-07-23T11:18:05","pubKey":{"s":"2303777410538172886271756794595982408449215299184180786707946414344825115577210309329094182704710373375176575047946743652593825273038473592524248046488588."},"ipfs_ID":"QmcTKsArf7aMicW1zNvMRXEJHbpEdfFF2wZi6HK7UdEYvs","stats":"2.16.2","rating":0,"region_code":""},{"id":"2.14.3","seeder":"1.2.85","free_space":99,"price":{"amount":70000000,"asset_id":"1.3.0"},"expiration":"2018-07-07T11:26:40","pubKey":{"s":"3743207398576779808066945562729802450508896326744651113099676580067412967050373989300299175261482010131674378978235327498648053632565557861526621363621025."},"ipfs_ID":"QmU4zusEFksdUoEnqk57LND5fFFdDjzDTCBGJsYahN56Tt","stats":"2.16.3","rating":0,"region_code":""}]}'))
                ));
        }

        $seeders = $this->sdk->getSeederApi()->listByUpload();

        foreach ($seeders as $seeder) {
            $this->assertInstanceOf(Seeder::class, $seeder);
        }
    }

    public function testListSeedersByRegion(): void
    {
        // TODO: No data
        $seeders = $this->sdk->getSeederApi()->listByRegion();
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testListSeedersByRating(): void
    {
        // TODO: No data
        $seeders = $this->sdk->getSeederApi()->listByRegion();
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

}