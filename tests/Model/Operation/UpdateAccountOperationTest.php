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
            '0220a1070000000000002200000102cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f030000010003000000000000000000000000000000000000',
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
            '0220a107000000000000220101000000000102cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f01000101000000000102cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f01000102cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f03000002000500000008000000000000000000000000000000000000',
            $updateAccount->toBytes()
        );
    }
}
