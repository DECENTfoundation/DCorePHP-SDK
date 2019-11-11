<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Model\General\MinerRewardInput;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class GeneralApiTest extends DCoreSDKTest
{
    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetInfo(): void
    {
        $info = self::$sdk->getGeneralApi()->info();
        $this->assertEquals('database_api', $info);
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetChainProps(): void
    {
        $chainProps = self::$sdk->getGeneralApi()->getChainProperties();

        $this->assertEquals('2.9.0', $chainProps->getId()->getId());
        $this->assertEquals('34b44a8df3c6910cbe4ca4656b4d23e8d6dc137b2ef8d1313d4b39fea05ff7be', $chainProps->getChainId());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetGlobalProps(): void
    {
        $globalProps = self::$sdk->getGeneralApi()->getGlobalProperties();

        $this->assertEquals('2.0.0', $globalProps->getId());
        $this->assertEquals('2048000', $globalProps->getParameters()->getMaximumBlockSize());
        $this->assertEquals(1001, $globalProps->getParameters()->getMaximumMinerCount());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetConfig(): void
    {
        $config = self::$sdk->getGeneralApi()->getConfiguration();
        $this->assertEquals('DCT', $config->getGrapheneSymbol());
        $this->assertEquals('DCT', $config->getGrapheneAddressPrefix());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testChainId(): void
    {
        $chainId = self::$sdk->getGeneralApi()->getChainId();
        $this->assertEquals('34b44a8df3c6910cbe4ca4656b4d23e8d6dc137b2ef8d1313d4b39fea05ff7be', $chainId);
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function testGetDynamicProperties(): void
    {
        $dynamicGlobalProps = self::$sdk->getGeneralApi()->getDynamicGlobalProperties();
        $this->assertEquals('2.1.0', $dynamicGlobalProps->getId());
    }

    /**
     * @throws Exception
     * @doesNotPerformAssertions
     */
    public function testTimeToMaintenance(): void
    {
        self::$sdk->getGeneralApi()->getTimeToMaintenance('2018-10-13T22:26:02.825');
    }

}
