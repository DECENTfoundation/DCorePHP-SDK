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
                    GetBlock::responseToModel(new BaseResponse('{"id":3,"result":{"previous":"000000094d148629bdafab2648fce14f44267150","timestamp":"2019-03-15T12:42:00","miner":"1.4.1","transaction_merkle_root":"0000000000000000000000000000000000000000","extensions":[],"miner_signature":"201214c80b5d7a371e11326d095584de86709fed6d4d217772e6aa3e9502ea971a1856ba9f5cb21730176ebeee687cca15c265f679cc872daf1a84628cdd6a3204","transactions":[]}}'))
                ));
        }

        $block = $this->sdk->getBlockApi()->get('10');

        $this->assertEquals('000000094d148629bdafab2648fce14f44267150', $block->getPrevious());
        $this->assertEquals('1.4.1', $block->getMiner()->getId());
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
                    GetBlockHeader::responseToModel(new BaseResponse('{"id":3,"result":{"previous":"000000094d148629bdafab2648fce14f44267150","timestamp":"2019-03-15T12:42:00","miner":"1.4.1","transaction_merkle_root":"0000000000000000000000000000000000000000","extensions":[]}}'))
                ));
        }

        $blockHeader = $this->sdk->getBlockApi()->getHeader('10');

        $this->assertEquals('000000094d148629bdafab2648fce14f44267150', $blockHeader->getPrevious());
        $this->assertEquals('1.4.1', $blockHeader->getMiner()->getId());
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