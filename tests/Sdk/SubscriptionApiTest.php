<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class SubscriptionApiTest extends DCoreSDKTest
{
    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     *
     * @doesNotPerformAssertions
     */
    public function testGet(): void
    {
        try {
            self::$sdk->getSubscriptionApi()->get(new ChainObject('2.15.0'));
        } catch (ObjectNotFoundException $exception) { }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllActiveByConsumer(): void
    {
        $subscriptions = self::$sdk->getSubscriptionApi()->getAllActiveByConsumer(new ChainObject('1.2.62'));

        if (empty($subscriptions)) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $subscription = reset($subscriptions);
        $this->assertEquals('1.2.62', $subscription->getFrom());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllActiveByAuthor(): void
    {
        $subscriptions = self::$sdk->getSubscriptionApi()->getAllActiveByAuthor(new ChainObject('1.2.62'));

        if (empty($subscriptions)) {
            $this->expectNotToPerformAssertions();
        } else {
            $subscription = reset($subscriptions);
            $this->assertEquals('1.2.62', $subscription->getTo());
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     *
     * @doesNotPerformAssertions
     */
    public function testGetAllByConsumer(): void
    {
        self::$sdk->getSubscriptionApi()->getAllByConsumer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), 10);
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     *
     * @doesNotPerformAssertions
     */
    public function testGetAllByAuthor(): void
    {
        self::$sdk->getSubscriptionApi()->getAllByAuthor(new ChainObject('1.2.82'));
    }
}
