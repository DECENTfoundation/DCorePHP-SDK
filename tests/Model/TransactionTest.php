<?php

namespace DCorePHPTests\Model;

use DateTime;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\BlockData;
use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\Memo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetFundPoolsOperation;
use DCorePHP\Model\Operation\AccountCreateOperation;
use DCorePHP\Model\Operation\AccountUpdateOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\Options;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Model\Transaction;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testSignTransfer(): void
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

        $operation = new TransferOperation();
        $operation
            ->setFrom(DCoreSDKTest::ACCOUNT_ID_1)
            ->setTo(DCoreSDKTest::ACCOUNT_ID_2)
            ->setAmount(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(1500000)
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
            )->setFee(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(500000));

        $transaction = new Transaction();
        $transaction
            ->setBlockData(BlockData::fromDynamicGlobalProps($dynamicGlobalProperties, '30'))
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($senderPrivateKeyWif);

        $this->assertEquals(
            '202ce584bb151776719959cadd9d88f93e1872083ea6c699105541eca34e16017263eefd5e849d18786188e693a42fe12075c251c6db141bcc39f196f2dd9cdd41',
            $transaction->getSignatures()[0]
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testSignRegisterAccount(): void
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

        $operation = new AccountCreateOperation();
        $operation
            ->setAccountName('mikeeee')
            ->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU')]))
            ->setRegistrar(new ChainObject('1.2.34'))
            ->setOptions($options)
            ->setFee((new AssetAmount())->setAmount(500000));

        $transaction = new Transaction();
        $transaction
            ->setBlockData(BlockData::fromDynamicGlobalProps($dynamicGlobalProperties, 30))
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertEquals(
            'e061b1d5fbff1fe80b5b010120a10700000000000022076d696b6565656501000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010001000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010002a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33030000000000000000000000000000000000000000',
            $transaction->toBytes()
        );
        $this->assertEquals(
            '206cd94c347e104009a429b8234f0898d71426254dabb6ec4a37242d0f0d3658dc5795f05e0554eef61ee2b71337c68d66803267bae6545c3fe39c4dfabbab4d2f',
            $transaction->getSignatures()[0]
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testSignUpdateAccount(): void
    {
        $dynamicGlobalProperties = new DynamicGlobalProps();
        $dynamicGlobalProperties
            ->setId('2.1.0')
            ->setHeadBlockNumber(3441407)
            ->setHeadBlockId('003482ff012880f806baa6f220538425804136be')
            ->setTime('2018-12-19T14:08:30+00:00')
            ->setCurrentMiner('1.4.9')
            ->setNextMaintenanceTime('2018-12-20T00:00:00+00:00')
            ->setLastBudgetTime('2018-12-19T00:00:00+00:00')
            ->setUnspentFeeBudget(11400166)
            ->setMinedRewards('308728000000')
            ->setMinerBudgetFromFees(22030422)
            ->setMinerBudgetFromRewards(639249000000)
            ->setAccountsRegisteredThisInterval(8)
            ->setRecentlyMissedCount(0)
            ->setCurrentAslot(9281631)
            ->setRecentSlotsFilled('317672346624442248850332726400554761855')
            ->setDynamicFlags(0)
            ->setLastIrreversibleBlockNum(3441407);

        $options = new Options();
        $options
            ->setMemoKey(DCoreSDKTest::PUBLIC_KEY_1)
            ->setVotingAccount(new ChainObject('1.2.3'))
            ->setAllowSubscription(false)
            ->setPricePerSubscribe((new AssetAmount())->setAmount(0)->setAssetId(new ChainObject('1.3.0')))
            ->setNumMiner(0)
            ->setVotes(['0:3'])
            ->setExtensions([])
            ->setSubscriptionPeriod(0);

        $operation = new AccountUpdateOperation();
        $operation
            ->setAccountId(new ChainObject('1.2.34'))
            ->setOptions($options)
            ->setFee((new AssetAmount())->setAmount(500000));

        $transaction = new Transaction();
        $transaction
            ->setBlockData(BlockData::fromDynamicGlobalProps($dynamicGlobalProperties, 30))
            ->setOperations([$operation])
            ->setExtensions([]);
        $transaction->getBlockData()->increment();

        $this->assertEquals(
            'ff82012880f8fd501a5c010220a10700000000000022000001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c03000001000300000000000000000000000000000000000000',
            $transaction->toBytes()
        );

        $transaction->sign(DCoreSDKTest::PRIVATE_KEY_1);

        $this->assertEquals(
            '1f6a0cad43d970a786385afd87ed7e0b70b8386e8496ded40a21d02bbab603c8923f9f914196ee0b0f5d2dd36b2f8aaaf37fbc0f73934be7829247bb8ea8595138',
            $transaction->getSignatures()[0]
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testSignFund(): void
    {
        $senderPrivateKeyWif = DCoreSDKTest::PRIVATE_KEY_1;

        /** @var DynamicGlobalProps $dynamicGlobalProperties */
        $dynamicGlobalProperties = $this
            ->getMockBuilder(DynamicGlobalProps::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHeadBlockNumber', 'getHeadBlockId', 'getTime', 'getRefBlockNum', 'getRefBlockPrefix', 'getExpiration'])
            ->getMock();
        $dynamicGlobalProperties->expects($this->any())->method('getHeadBlockNumber')->willReturn(1);
        $dynamicGlobalProperties->expects($this->any())->method('getHeadBlockId')->willReturn('1');
        $dynamicGlobalProperties->expects($this->any())->method('getTime')->willReturn(new DateTime());
        $dynamicGlobalProperties->expects($this->any())->method('getRefBlockNum')->willReturn(57172);
        $dynamicGlobalProperties->expects($this->any())->method('getRefBlockPrefix')->willReturn('2795193508');
        $dynamicGlobalProperties->expects($this->any())->method('getExpiration')->willReturn(new DateTime('2019-07-17T20:42:38'));

        $operation = new AssetFundPoolsOperation();
        $operation
            ->setFrom(DCoreSDKTest::ACCOUNT_ID_1)
            ->setUia((new AssetAmount())->setAssetId(new ChainObject('1.3.82'))->setAmount(100))
            ->setDct((new AssetAmount())->setAssetId(new ChainObject('1.3.0'))->setAmount(1000))
            ->setFee((new AssetAmount())->setAssetId(new ChainObject('1.3.0'))->setAmount(500000))
        ;

        $transaction = new Transaction();
        $transaction
            ->setChainId('34b44a8df3c6910cbe4ca4656b4d23e8d6dc137b2ef8d1313d4b39fea05ff7be')
            ->setBlockData(new BlockData(
                $dynamicGlobalProperties->getRefBlockNum(),
                $dynamicGlobalProperties->getRefBlockPrefix(),
                $dynamicGlobalProperties->getExpiration())
            )
            ->setOperations([$operation])
            ->setExtensions([])
        ;

        $this->assertEquals(
            '54dfa4449ba63e882f5d012120a1070000000000001b640000000000000052e803000000000000000000',
            $transaction->toBytes()
        );

        $transaction->sign($senderPrivateKeyWif);

        $this->assertEquals(
            '2043114653d1fcd6274276425a854476faed9b33f7e9e0c17db7a82b1c5a6699927123e6255cb21424603340a7ccfba7adc6985d1cfeced23cd61ecaf1dee7e4d1',
            $transaction->getSignatures()[0]
        );
    }
}
