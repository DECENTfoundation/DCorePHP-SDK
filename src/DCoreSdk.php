<?php

namespace DCorePHP;

abstract class DCoreSdk
{
    public const DCT_CHAIN_ID = '17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc';

    /** @var string */
    private $dcoreApiUrl;
    /** @var string */
    private $dcoreWebsocketUrl;
    /** @var bool */
    private $debug;

    public function __construct(string $dcoreApiUrl, string $dcoreWebsocketUrl, bool $debug = false)
    {
        $this->dcoreApiUrl = $dcoreApiUrl;
        $this->dcoreWebsocketUrl = $dcoreWebsocketUrl;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function getDcoreApiUrl(): string
    {
        return $this->dcoreApiUrl;
    }

    /**
     * @param string $dcoreApiUrl
     * @return DCoreSdk
     */
    public function setDcoreApiUrl(string $dcoreApiUrl): DCoreSdk
    {
        $this->dcoreApiUrl = $dcoreApiUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getDcoreWebsocketUrl(): string
    {
        return $this->dcoreWebsocketUrl;
    }

    /**
     * @param string $dcoreWebsocketUrl
     * @return DCoreSdk
     */
    public function setDcoreWebsocketUrl(string $dcoreWebsocketUrl): DCoreSdk
    {
        $this->dcoreWebsocketUrl = $dcoreWebsocketUrl;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return DCoreSdk
     */
    public function setDebug(bool $debug): DCoreSdk
    {
        $this->debug = $debug;

        return $this;
    }

}
