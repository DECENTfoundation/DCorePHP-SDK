<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetBlock;
use DCorePHP\Net\Model\Request\GetBlockHeader;
use DCorePHP\Net\Model\Request\HeadBlockTime;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class BlockApiTest extends DCoreSDKTest
{
    public function testGet(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_block",["10"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetBlock::responseToModel(new BaseResponse('{"id":3,"result":{"previous":"00000009fdaa51c1bbb7ca167aa87cf36ef330a1","timestamp":"2018-04-26T11:24:45","miner":"1.4.8","transaction_merkle_root":"0000000000000000000000000000000000000000","extensions":[],"miner_signature":"204d599f40e6413e6c1c0a009e8ae244f56872ca4f501d42ba61c8a8aa6e08eff9399b71973512fb40cdb33a7faf9d8c743f618f348ef00c39dbd0119a0e934028","transactions":[]}}'))
                ));
        }

        $block = $this->sdk->getBlockApi()->get('10');

        $this->assertEquals('00000009fdaa51c1bbb7ca167aa87cf36ef330a1', $block->getPrevious());
        $this->assertEquals('1.4.8', $block->getMiner()->getId());
    }

    public function testGetHeader(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_block_header",["10"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetBlockHeader::responseToModel(new BaseResponse('{"id":3,"result":{"previous":"00000009fdaa51c1bbb7ca167aa87cf36ef330a1","timestamp":"2018-04-26T11:24:45","miner":"1.4.8","transaction_merkle_root":"0000000000000000000000000000000000000000","extensions":[]}}'))
                ));
        }

        $blockHeader = $this->sdk->getBlockApi()->getHeader('10');

        $this->assertEquals('00000009fdaa51c1bbb7ca167aa87cf36ef330a1', $blockHeader->getPrevious());
        $this->assertEquals('1.4.8', $blockHeader->getMiner()->getId());
    }

    /**
     * @throws \Exception
     */
    public function testGetHeadTime(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"head_block_time",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    HeadBlockTime::responseToModel(new BaseResponse('{"id":3,"result":"2019-04-04T11:37:10"}'))
                ));
        }

        $headTime = $this->sdk->getBlockApi()->getHeadTime();

        $this->assertInstanceOf(\DateTime::class, $headTime);
    }
}