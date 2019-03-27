<?php

namespace DCorePHPTests;

use DCorePHP\DCoreApi;
use DCorePHP\Net\JsonRpc;
use DCorePHP\Net\Websocket;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class DCoreSDKTest extends TestCase
{
    public const ACCOUNT_ID_1 = '1.2.34';
    public const ACCOUNT_ID_2 = '1.2.35';
    public const PRIVATE_KEY_1 = '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn';
    public const PRIVATE_KEY_2 = '5JVHeRffGsKGyDf7T9i9dBbzVHQrYprYeaBQo2VCSytj7BxpMCq';
    public const PUBLIC_KEY_1 = 'DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz';
    public const PUBLIC_KEY_2 = 'DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP';

    /** @var DCoreApi */
    protected $sdk;
    /**
     * if this property is set to true, it won't do any external requests
     * if this property is set to false, it will do requests against staging blockchain
     *
     * @var bool
     */
    protected $mockServer = true;
    /** @var MockObject|Websocket|null */
    protected $websocketMock;
    /** @var MockObject|JsonRpc|null */
    protected $jsonRpcMock;

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->sdk = new DCoreApi(
            'https://stagesocket.decentgo.com:8090/',
            'wss://stagesocket.decentgo.com:8090',
            true
        );

        if ($this->mockServer) {
            $this->sdk = new DCoreApi(
                'http://127.0.0.1:8089/',
                'wss://127.0.0.1:8090',
                true
            );

            $this->jsonRpcMock = $this
                ->getMockBuilder(JsonRpc::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->websocketMock = $this
                ->getMockBuilder(Websocket::class)
                ->disableOriginalConstructor()
                ->getMock();

            $reflectionClass = new \ReflectionClass(get_class($this->sdk));

            $jsonRpcProperty = $reflectionClass->getProperty('jsonRpc');
            $jsonRpcProperty->setAccessible(true);
            $jsonRpcProperty->setValue($this->sdk, $this->jsonRpcMock);

            $websocketProperty = $reflectionClass->getProperty('websocket');
            $websocketProperty->setAccessible(true);
            $websocketProperty->setValue($this->sdk, $this->websocketMock);
        }
    }

    protected function tearDown()
    {
        $this->sdk = null;
    }
}
