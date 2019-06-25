<?php

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\UpdateAccountOperation;
use DCorePHP\Model\Options;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class UpdateAccountOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testToBytes(): void
    {
        $updateAccount = new UpdateAccountOperation();
        $updateAccount
            ->setAccountId(new ChainObject('1.2.34'))
            ->setOptions(
                (new Options())
                    ->setMemoKey(DCoreSDKTest::PUBLIC_KEY_1)
                    ->setVotingAccount(new ChainObject('1.2.3'))
                    ->setNumMiner(0)
                    ->setVotes(['0:3'])
                    ->setExtensions([])
                    ->setAllowSubscription(false)
                    ->setSubscriptionPeriod(0)
                    ->setPricePerSubscribe(
                        (new AssetAmount())
                            ->setAmount(0)
                            ->setAssetId(new ChainObject('1.3.0'))
                    )
            )->setFee(
                (new AssetAmount())
                    ->setAmount(500000)
                    ->setAssetId(new ChainObject('1.3.0')))
        ;

        $this->assertEquals(
            '0220a10700000000000022000001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c030000010003000000000000000000000000000000000000',
            $updateAccount->toBytes()
        );

        $updateAccount = new UpdateAccountOperation();
        $updateAccount
            ->setAccountId(new ChainObject('1.2.34'))
            ->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue(DCoreSDKTest::PUBLIC_KEY_1)]))
            ->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue(DCoreSDKTest::PUBLIC_KEY_1)]))
            ->setOptions(
                (new Options())
                    ->setMemoKey(DCoreSDKTest::PUBLIC_KEY_1)
                    ->setVotingAccount(new ChainObject('1.2.3'))
                    ->setNumMiner(0)
                    ->setVotes(['0:5', '0:8'])
                    ->setExtensions([])
                    ->setAllowSubscription(false)
                    ->setSubscriptionPeriod(0)
                    ->setPricePerSubscribe(
                        (new AssetAmount())
                            ->setAmount(0)
                            ->setAssetId(new ChainObject('1.3.0'))
                    )
            )->setFee(
                (new AssetAmount())
                    ->setAmount(500000)
                    ->setAssetId(new ChainObject('1.3.0')))
        ;

        $this->assertEquals(
            '0220a1070000000000002201010000000001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c010001010000000001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c010001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c03000002000500000008000000000000000000000000000000000000',
            $updateAccount->toBytes()
        );
    }
}
