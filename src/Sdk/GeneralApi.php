<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\General\ChainProperty;
use DCorePHP\Model\General\Configuration;
use DCorePHP\Model\General\GlobalProperty;
use DCorePHP\Model\General\MinerRewardInput;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetChainProperties;
use DCorePHP\Net\Model\Request\GetConfiguration;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetGlobalProperties;
use DCorePHP\Net\Model\Request\GetTimeToMaintenance;
use DCorePHP\Net\Model\Request\Info;

class GeneralApi extends BaseApi implements GeneralApiInterface
{
    /**
     * @inheritdoc
     */
    public function getChainProperties(): ChainProperty
    {
        return $this->dcoreApi->requestWebsocket(new GetChainProperties());
    }

    /**
     * @inheritdoc
     */
    public function getGlobalProperties(): GlobalProperty
    {
        return $this->dcoreApi->requestWebsocket(new GetGlobalProperties());
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration(): Configuration
    {
        return $this->dcoreApi->requestWebsocket(new GetConfiguration());
    }

    /**
     * @inheritdoc
     */
    public function getChainId(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetChainId());
    }

    /**
     * @inheritdoc
     */
    public function getDynamicGlobalProperties(): DynamicGlobalProps
    {
        return $this->dcoreApi->requestWebsocket(new GetDynamicGlobalProperties());
    }

    /**
     * @inheritdoc
     */
    public function getTimeToMaintenance(string $time): MinerRewardInput
    {
        return $this->dcoreApi->requestWebsocket(new GetTimeToMaintenance($time));
    }

    /**
     * @inheritdoc
     */
    public function info(): string
    {
        return $this->dcoreApi->requestWebsocket(new Info());
    }
}