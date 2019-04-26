<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetSubscription;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByConsumer;
use DCorePHP\Net\Model\Request\ListSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListSubscriptionsByConsumer;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class SubscriptionApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     */
    public function testGet(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_subscription",["2.15.0"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetSubscription::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.15.0","from":"1.2.62","to":"1.2.76","expiration":"2019-04-08T11:14:35","automatic_renewal":false}}'))
                ));
        }

        $subscription = $this->sdk->getSubscriptionApi()->get(new ChainObject('2.15.0'));

        $this->assertEquals('1.2.62', $subscription->getFrom());
        $this->assertEquals('1.2.76', $subscription->getTo());
        $this->assertFalse($subscription->isRenewal());
    }

    /**
     * @throws ValidationException
     */
    public function testGetAllActiveByConsumer(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_active_subscriptions_by_consumer",["1.2.62",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListActiveSubscriptionsByConsumer::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.15.3","from":"1.2.62","to":"1.2.76","expiration":"2022-05-06T07:25:50","automatic_renewal":true}]}'))
                ));
        }

        $subscriptions = $this->sdk->getSubscriptionApi()->getAllActiveByConsumer(new ChainObject('1.2.62'));

        if (empty($subscriptions) && !$this->websocketMock) {
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_active_subscriptions_by_author",["1.2.62",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListActiveSubscriptionsByAuthor::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.15.3","from":"1.2.27","to":"1.2.62","expiration":"2019-05-06T07:25:50","automatic_renewal":true}]}'))
                ));
        }

        $subscriptions = $this->sdk->getSubscriptionApi()->getAllActiveByAuthor(new ChainObject('1.2.62'));

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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_subscriptions_by_consumer",["1.2.62",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListSubscriptionsByConsumer::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.15.0","from":"1.2.62","to":"1.2.76","expiration":"2019-04-08T11:14:35","automatic_renewal":false}]}'))
                ));
        }

        $subscriptions = $this->sdk->getSubscriptionApi()->getAllByConsumer(new ChainObject('1.2.62'));
        $subscription = reset($subscriptions);

        $this->assertEquals('1.2.62', $subscription->getFrom());
    }

    /**
     * @throws ValidationException
     */
    public function testGetAllByAuthor(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"list_subscriptions_by_author",["1.2.62",100]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    ListSubscriptionsByAuthor::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"2.15.3","from":"1.2.27","to":"1.2.62","expiration":"2019-05-06T07:25:50","automatic_renewal":true},{"id":"2.15.4","from":"1.2.76","to":"1.2.62","expiration":"2018-06-19T09:29:00","automatic_renewal":false},{"id":"2.15.5","from":"1.2.59","to":"1.2.62","expiration":"2018-06-19T09:32:35","automatic_renewal":false}]}'))
                ));
        }

        $subscriptions = $this->sdk->getSubscriptionApi()->getAllByAuthor(new ChainObject('1.2.62'));
        $subscription = reset($subscriptions);

        $this->assertEquals('1.2.62', $subscription->getTo());
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