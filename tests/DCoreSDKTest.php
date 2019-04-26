<?php

namespace DCorePHPTests;

use DCorePHP\DCoreApi;
use DCorePHP\Net\JsonRpc;
use DCorePHP\Net\Websocket;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class DCoreSDKTest extends TestCase
{
    public const ACCOUNT_ID_1 = '1.2.27';
    public const ACCOUNT_ID_2 = '1.2.28';
    public const ACCOUNT_NAME_1 = 'public-account-9';
    public const ACCOUNT_NAME_2 = 'public-account-10';
    public const PRIVATE_KEY_1 = '5Hxwqx6JJUBYWjQNt8DomTNJ6r6YK8wDJym4CMAH1zGctFyQtzt';
    public const PRIVATE_KEY_2 = '5JMpT5C75rcAmuUB81mqVBXbmL1BKea4MYwVK6voMQLvigLKfrE';
    public const PUBLIC_KEY_1 = 'DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb';
    public const PUBLIC_KEY_2 = 'DCT5PwcSiigfTPTwubadt85enxMFC18TtVoti3gnTbG7TN9f9R3Fp';

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
            'https://testnet-api.dcore.io/',
            'wss://testnet-api.dcore.io',
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
