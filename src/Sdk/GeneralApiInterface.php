<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\General\ChainProperty;
use DCorePHP\Model\General\Config;
use DCorePHP\Model\General\GlobalProperty;
use DCorePHP\Model\General\MinerRewardInput;

interface GeneralApiInterface
{
    /**
     * Retrieve properties associated with the chain
     *
     * @return ChainProperty chain id and immutable chain parameters
     */
    public function getChainProperties(): ChainProperty;

    /**
     * Retrieve global properties. This object contains all of the properties of the blockchain that are fixed
     * or that change only once per maintenance interval such as the current list of miners, block interval, etc.
     *
     * @return GlobalProperty object
     */
    public function getGlobalProperties(): GlobalProperty;

    /**
     * Retrieve compile-time constants
     *
     * @return Config configured constants
     */
    public function getConfig(): Config;

    /**
     * Get the chain ID
     *
     * @return string the chain ID identifying blockchain network
     */
    public function getChainId(): string;

    /**
     * Retrieve the dynamic properties. The returned object contains information that changes every block interval,
     * such as the head block number, the next miner, etc.
     *
     * @return DynamicGlobalProps object
     */
    public function getDynamicGlobalProperties(): DynamicGlobalProps;

    /**
     * Get remaining time to next maintenance interval from given time
     *
     * @param $time - reference time
     * @return MinerRewardInput remaining time to next maintenance interval along with some additional data
     */
    public function getTimeToMaintenance(string $time): MinerRewardInput;

    /**
     * Get the name of the API
     *
     * @return string the name of the API
     */
    public function info(): string;
}