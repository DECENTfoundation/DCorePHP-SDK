<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\Memo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\CreateAccountParameters;
use DCorePHP\Model\Operation\CreateAccount;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Model\Transaction;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testSignTransfer()
    {
        $senderPrivateKeyWif = DCoreSDKTest::PRIVATE_KEY_1;
        $senderPublicKeyWif = DCoreSDKTest::PUBLIC_KEY_1;
        $recipientPublicKeyWif = DCoreSDKTest::PUBLIC_KEY_2;

        $dynamicGlobalProperties = new DynamicGlobalProps();
        $dynamicGlobalProperties
            ->setId('2.1.0')
            ->setHeadBlockNumber(599091)
            ->setHeadBlockId('00092433e84dedb18c9b9a378cfea8cdfbb2b637')
            ->setTime('2018-06-04T12:25:00+00:00')
            ->setCurrentMiner('1.4.8')
            ->setNextMaintenanceTime('2018-06-05T00:00:00+00:00')
            ->setLastBudgetTime('2018-06-04T00:00:00+00:00')
            ->setUnspentFeeBudget(96490)
            ->setMinedRewards('301032000000')
            ->setMinerBudgetFromFees(169714)
            ->setMinerBudgetFromRewards(639249000000)
            ->setAccountsRegisteredThisInterval(1)
            ->setRecentlyMissedCount(0)
            ->setCurrentAslot(5859543)
            ->setRecentSlotsFilled('329648380685469039951165571643239038463')
            ->setDynamicFlags(0)
            ->setLastIrreversibleBlockNum(599091);

        $operation = new Transfer2();
        $operation
            ->setFrom('1.2.34')
            ->setTo('1.2.35')
            ->setAmount(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(1500000)
            )->setFee(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(500000)
            )->setMemo(
                (new Memo())
                    ->setFrom(Address::decodeCheckNull($senderPublicKeyWif))
                    ->setTo(Address::decodeCheckNull($recipientPublicKeyWif))
                    ->setNonce('735604672334802432')
                    ->setMessage(Crypto::getInstance()->encryptWithChecksum( // 4bc2a1ee670302ceddb897c2d351fa0496ff089c934e35e030f8ae4f3f9397a7
                        'hello memo here i am',
                        PrivateKey::fromWif($senderPrivateKeyWif),
                        PublicKey::fromWif($recipientPublicKeyWif),
                        '735604672334802432'
                    ))
            );

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($senderPrivateKeyWif);

        $this->assertEquals(
            '203c168ef8b88e5702cedd7ee2985a67a63fb15a58023323828c0b843c37eb4a6d1b45665414488d83262f7116ac6a0116943d512352c8e858fe636b3bec195265',
            $transaction->getSignature()
        );
    }

    public function testSignRegisterAccount()
    {
        $dynamicGlobalProperties = new DynamicGlobalProps();
        $dynamicGlobalProperties
            ->setId('2.1.0')
            ->setHeadBlockNumber(483808)
            ->setHeadBlockId('000761e0b1d5fbffd947cb42b397d355e4e25246')
            ->setTime('2018-05-28T11:29:05+00:00')
            ->setCurrentMiner('1.4.1')
            ->setNextMaintenanceTime('2018-05-29T00:00:00+00:00')
            ->setLastBudgetTime('2018-05-28T00:00:00+00:00')
            ->setUnspentFeeBudget(6411522)
            ->setMinedRewards('278314000000')
            ->setMinerBudgetFromFees(11345954)
            ->setMinerBudgetFromRewards(639249000000)
            ->setAccountsRegisteredThisInterval(0)
            ->setRecentlyMissedCount(0)
            ->setCurrentAslot(5737933)
            ->setRecentSlotsFilled('169974867696766918687181421356601016319')
            ->setDynamicFlags(0)
            ->setLastIrreversibleBlockNum(483808);

        $createAccountParameters = new CreateAccountParameters();
        $createAccountParameters
            ->setMemoKey('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')
            ->setVotingAccount(new ChainObject('1.2.3'))
            ->setAllowSubscription(false)
            ->setPricePerSubscribe((new AssetAmount())->setAmount(0)->setAssetId(new ChainObject('1.3.0')))
            ->setNumMiner(0)
            ->setVotes([])
            ->setExtensions([])
            ->setSubscriptionPeriod(0);

        $operation = new CreateAccount();
        $operation
            ->setAccountName('mikeeee')
            ->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setRegistrar(new ChainObject('1.2.34'))
            ->setOptions($createAccountParameters)
            ->setName(CreateAccount::OPERATION_NAME)
            ->setType(CreateAccount::OPERATION_TYPE)
            ->setFee((new AssetAmount())->setAmount(500000));

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertEquals(
            'e061b1d5fbff20e80b5b010120a10700000000000022076d696b6565656501000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010001000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010002a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33030000000000000000000000000000000000000000',
            $transaction->toBytes()
        );
        $this->assertEquals(
            '1f246288a9167fa71269bdb2ceaefa02334cecf1363db112b560476aad76ac1d5a66e17ffb6a209261aa6052142373955fb007ed815a46ee3cca1402862a85ae19',
            $transaction->getSignature()
        );
    }
}