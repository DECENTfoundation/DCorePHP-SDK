<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\General\ChainProperty;
use DCorePHP\Model\General\GlobalProperty;
use DCorePHP\Model\General\MinerRewardInput;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetChainProperties;
use DCorePHP\Net\Model\Request\GetConfig;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetGlobalProperties;
use DCorePHP\Net\Model\Request\GetTimeToMaintenance;
use DCorePHP\Net\Model\Request\Info;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class GeneralApiTest extends DCoreSDKTest
{
    public function testGetInfo(): void
    {
        $info = self::$sdk->getGeneralApi()->info();
        $this->assertEquals('database_api', $info);
    }

    public function testGetChainProps(): void
    {
        $chainProps = self::$sdk->getGeneralApi()->getChainProperties();

        $this->assertInstanceOf(ChainProperty::class, $chainProps);
        $this->assertEquals('2.9.0', $chainProps->getId()->getId());
        $this->assertEquals('34b44a8df3c6910cbe4ca4656b4d23e8d6dc137b2ef8d1313d4b39fea05ff7be', $chainProps->getChainId());
    }

    public function testGetGlobalProps(): void
    {
        $globalProps = self::$sdk->getGeneralApi()->getGlobalProperties();

        $this->assertInstanceOf(GlobalProperty::class, $globalProps);
        $this->assertEquals('2.0.0', $globalProps->getId());
        $this->assertEquals('2048000', $globalProps->getParameters()->getMaximumBlockSize());
        $this->assertEquals(1001, $globalProps->getParameters()->getMaximumMinerCount());
    }

    public function testGetConfig(): void
    {
        $config = self::$sdk->getGeneralApi()->getConfig();
        $this->assertEquals('DCT', $config->getGrapheneSymbol());
        $this->assertEquals('DCT', $config->getGrapheneAddressPrefix());
    }

    public function testChainId(): void
    {
        $chainId = self::$sdk->getGeneralApi()->getChainId();
        $this->assertEquals('34b44a8df3c6910cbe4ca4656b4d23e8d6dc137b2ef8d1313d4b39fea05ff7be', $chainId);
    }

    public function testGetDynamicProperties(): void
    {
        $dynamicGlobalProps = self::$sdk->getGeneralApi()->getDynamicGlobalProperties();
        $this->assertEquals('2.1.0', $dynamicGlobalProps->getId());
    }

    /**
     * @throws \Exception
     */
    public function testTimeToMaintenance(): void
    {
        $minerReward = self::$sdk->getGeneralApi()->getTimeToMaintenance('2018-10-13T22:26:02.825');
        $this->assertInstanceOf(MinerRewardInput::class, $minerReward);
    }

}
