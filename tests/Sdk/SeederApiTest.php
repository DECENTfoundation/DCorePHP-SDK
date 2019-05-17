<?php


namespace DCorePHPTests\Sdk;


use DCorePHP\DCoreApi;
use DCorePHP\Model\Content\Seeder;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetSeederAbstract;
use DCorePHP\Net\Model\Request\ListSeedersByPrice;
use DCorePHP\Net\Model\Request\ListSeedersByUpload;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class SeederApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetSeeder(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"get_seeder",["1.2.17"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    GetSeederAbstract::responseToModel(new BaseResponse('{"id":1,"result":{"id":"2.14.0","seeder":"1.2.17","free_space":9947,"price":{"amount":10000000,"asset_id":"1.3.0"},"expiration":"2019-05-14T07:41:30","pubKey":{"s":"388623995520027680257080274907334470292881241518810412591176467398195525710484619373465376826137058931903619934039623141738312819768319215775577353874580."},"ipfs_ID":"QmaSFf3Vjzb2u13RihTJ2UPcufp4htmZS1YNzMECNBnYGJ","stats":"2.16.0","rating":0,"region_code":""}}'))
                ));
        }

        $seeder = $this->sdk->getSeederApi()->get(new ChainObject('1.2.17'));
        $this->assertEquals('1.2.17', $seeder->getSeeder()->getId());
    }

    public function testListSeedersByPrice(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"list_seeders_by_price",[100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    ListSeedersByPrice::responseToModel(new BaseResponse('{"id":1,"result":[{"id":"2.14.0","seeder":"1.2.17","free_space":9949,"price":{"amount":10000000,"asset_id":"1.3.0"},"expiration":"2019-04-24T07:41:30","pubKey":{"s":"388623995520027680257080274907334470292881241518810412591176467398195525710484619373465376826137058931903619934039623141738312819768319215775577353874580."},"ipfs_ID":"QmaSFf3Vjzb2u13RihTJ2UPcufp4htmZS1YNzMECNBnYGJ","stats":"2.16.0","rating":0,"region_code":""},{"id":"2.14.1","seeder":"1.2.18","free_space":9949,"price":{"amount":10000000,"asset_id":"1.3.0"},"expiration":"2019-04-24T07:43:00","pubKey":{"s":"10058760027158889177021467917662717042053283357954039688796117608068453071200522247554188812489590970979412575027557111588607466813591551279487589124905834."},"ipfs_ID":"QmfQ8zERxAgAE7qqKVbj24L1iaDtSGRWxQo6gDJjm6qv2T","stats":"2.16.1","rating":0,"region_code":""}]}'))
                ));
        }

        $seeders = $this->sdk->getSeederApi()->listByPrice();
        foreach ($seeders as $seeder) {
            $this->assertInstanceOf(Seeder::class, $seeder);
        }
    }

    public function testListSeedersByUpload(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->once())
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[0,"list_seeders_by_upload",[100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    ListSeedersByUpload::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.14.0","seeder":"1.2.17","free_space":9949,"price":{"amount":10000000,"asset_id":"1.3.0"},"expiration":"2019-04-24T07:41:30","pubKey":{"s":"388623995520027680257080274907334470292881241518810412591176467398195525710484619373465376826137058931903619934039623141738312819768319215775577353874580."},"ipfs_ID":"QmaSFf3Vjzb2u13RihTJ2UPcufp4htmZS1YNzMECNBnYGJ","stats":"2.16.0","rating":0,"region_code":""},{"id":"2.14.1","seeder":"1.2.18","free_space":9949,"price":{"amount":10000000,"asset_id":"1.3.0"},"expiration":"2019-04-24T07:43:00","pubKey":{"s":"10058760027158889177021467917662717042053283357954039688796117608068453071200522247554188812489590970979412575027557111588607466813591551279487589124905834."},"ipfs_ID":"QmfQ8zERxAgAE7qqKVbj24L1iaDtSGRWxQo6gDJjm6qv2T","stats":"2.16.1","rating":0,"region_code":""}]}'))
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