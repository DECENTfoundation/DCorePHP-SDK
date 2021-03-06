<?php

namespace DCorePHPTests\Model;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\CreateAccountOperation;
use DCorePHP\Model\Options;
use DCorePHP\Model\Subscription\AuthMap;
use PHPUnit\Framework\TestCase;

class CreateAccountOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testToBytes(): void
    {
        $options = new Options();
        $options
            ->setMemoKey('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')
            ->setVotingAccount(new ChainObject('1.2.3'))
            ->setAllowSubscription(false)
            ->setPricePerSubscribe((new AssetAmount())->setAmount(0)->setAssetId(new ChainObject('1.3.0')))
            ->setNumMiner(0)
            ->setVotes([])
            ->setExtensions([])
            ->setSubscriptionPeriod(0);

        $registerAccount = new CreateAccountOperation();
        $registerAccount
            ->setAccountName('mikeeeee')
            ->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setRegistrar(new ChainObject('1.2.34'))
            ->setOptions($options)
            ->setName(CreateAccountOperation::OPERATION_NAME)
            ->setType(CreateAccountOperation::OPERATION_TYPE)
            ->setFee((new AssetAmount())->setAmount(0));

        $this->assertEquals(
            '0100000000000000000022086d696b656565656501000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010001000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010002a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd330300000000000000000000000000000000000000',
            $registerAccount->toBytes()
        );
    }
}
