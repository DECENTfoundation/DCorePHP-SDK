<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Subscription\Subscription;
use DCorePHP\Net\Model\Request\GetSubscription;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListActiveSubscriptionsByConsumer;
use DCorePHP\Net\Model\Request\ListSubscriptionsByAuthor;
use DCorePHP\Net\Model\Request\ListSubscriptionsByConsumer;

class SubscriptionApi extends BaseApi implements SubscriptionApiInterface
{
    /**
     * @inheritdoc
     */
    public function get(ChainObject $id): Subscription
    {
        $subscription = $this->dcoreApi->requestWebsocket(new GetSubscription($id));
        if ($subscription instanceof Subscription) {
            return $subscription;
        }
        throw new ObjectNotFoundException("Subscription with id $id not found!");
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByConsumer(ChainObject $consumer, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListActiveSubscriptionsByConsumer($consumer, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllActiveByAuthor(ChainObject $author, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListActiveSubscriptionsByAuthor($author, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllByConsumer(ChainObject $consumer, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSubscriptionsByConsumer($consumer, $count));
    }

    /**
     * @inheritdoc
     */
    public function getAllByAuthor(ChainObject $author, int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSubscriptionsByAuthor($author, $count));
    }
}