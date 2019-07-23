<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetBlock;
use DCorePHP\Net\Model\Request\GetBlockHeader;
use DCorePHP\Net\Model\Request\HeadBlockTime;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class BlockApiTest extends DCoreSDKTest
{
    public function testGet(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $block = self::$sdk->getBlockApi()->get('10');
//
//        $this->assertEquals('00000009f320dfe4be64b58942b372e02f554c42', $block->getPrevious());
//        $this->assertEquals('1.4.8', $block->getMiner()->getId());
    }

    public function testGetHeader(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $blockHeader = self::$sdk->getBlockApi()->getHeader('10');
//
//        $this->assertEquals('00000009f320dfe4be64b58942b372e02f554c42', $blockHeader->getPrevious());
//        $this->assertEquals('1.4.8', $blockHeader->getMiner()->getId());
    }

    /**
     * @throws \Exception
     */
    public function testGetHeadTime(): void
    {
        $headTime = self::$sdk->getBlockApi()->getHeadTime();

        $this->assertInstanceOf(\DateTime::class, $headTime);
    }
}
