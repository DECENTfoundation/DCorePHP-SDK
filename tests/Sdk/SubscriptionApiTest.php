<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetSubscription;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByConsumer;
use DCorePHP\Net\Model\Request\ListSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListSubscriptionsByConsumer;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class SubscriptionApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     */
    public function testGet(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $subscription = self::$sdk->getSubscriptionApi()->get(new ChainObject('2.15.0'));
//
//        $this->assertEquals('2.15.0', $subscription->getId()->getId());
//        $this->assertFalse($subscription->isRenewal());
    }

    /**
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
     * @throws ValidationException
     */
    public function testGetAllByConsumer(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $subscriptions = self::$sdk->getSubscriptionApi()->getAllByConsumer(new ChainObject('1.2.83'));
//        $subscription = reset($subscriptions);
//
//        $this->assertEquals('1.2.83', $subscription->getFrom()->getId());
    }

    /**
     * @throws ValidationException
     */
    public function testGetAllByAuthor(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $subscriptions = self::$sdk->getSubscriptionApi()->getAllByAuthor(new ChainObject('1.2.82'));
//        $subscription = reset($subscriptions);
//
//        $this->assertEquals('1.2.82', $subscription->getTo()->getId());
    }

    public function testSubscribeToAuthor(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSubscribeByAuthor(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSetSubscription(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSetAutomaticRenewalOfSubscription(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testListActiveSubscriptionsByConsumer(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testListSubscriptionsByConsumer(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testListActiveSubscriptionsByAuthor(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testListSubscriptionsByAuthor(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

}
