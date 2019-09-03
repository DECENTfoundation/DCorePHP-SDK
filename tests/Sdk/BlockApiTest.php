<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetBlock;
use DCorePHP\Net\Model\Request\GetBlockHeader;
use DCorePHP\Net\Model\Request\HeadBlockTime;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use Exception;

class BlockApiTest extends DCoreSDKTest
{
    /**
     * @doesNotPerformAssertions
     */
    public function testGet(): void
    {
        self::$sdk->getBlockApi()->get('10');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testGetHeader(): void
    {
        self::$sdk->getBlockApi()->getHeader('10');
    }

    /**
     * @throws Exception
     * @doesNotPerformAssertions
     */
    public function testGetHeadTime(): void
    {
        self::$sdk->getBlockApi()->getHeadTime();
    }
}
