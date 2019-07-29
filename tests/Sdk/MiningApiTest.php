<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\MinerVotes;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Mining\MinerVotingInfo;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\GetAccountById;
use DCorePHP\Net\Model\Request\GetActualVotes;
use DCorePHP\Net\Model\Request\GetAssetPerBlock;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetMinerByAccount;
use DCorePHP\Net\Model\Request\GetMinerCount;
use DCorePHP\Net\Model\Request\GetMiners;
use DCorePHP\Net\Model\Request\GetNewAssetPerBlock;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\LookupMinerAccounts;
use DCorePHP\Net\Model\Request\LookupVoteIds;
use DCorePHP\Net\Model\Request\SearchMinerVoting;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class MiningApiTest extends DCoreSDKTest
{
    public function testGetActualVotes(): void
    {
        $votes = self::$sdk->getMiningApi()->getActualVotes();
        foreach ($votes as $vote) {
            $this->assertInstanceOf(MinerVotes::class, $vote);
        }
    }

    public function testGetAssetPerBlock(): void
    {
        $asset = self::$sdk->getMiningApi()->getAssetPerBlock('100');
        $this->assertEquals('0', $asset);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFeedsByMiner(): void
    {
        $feeds = self::$sdk->getMiningApi()->getFeedsByMiner(new ChainObject('1.2.4'));
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetMinerByAccount(): void
    {
        $miner = self::$sdk->getMiningApi()->getMinerByAccount(new ChainObject('1.2.4'));
        $this->assertEquals('1.2.4', $miner->getMinerAccount()->getId());
    }

    public function testGetMinerCount(): void
    {
        $count = self::$sdk->getMiningApi()->getMinerCount();
        $this->assertRegExp('/^[0-9]+$/', $count);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetMiners(): void
    {
        $minersRelative = self::$sdk->getMiningApi()->listMinersRelative('', 2);
        $minersChainObjects = array_map(function ($minerId) { return $minerId->getId();}, $minersRelative);
        $miners = self::$sdk->getMiningApi()->getMiners($minersChainObjects);

        $this->assertEquals(2, sizeof($miners));
        foreach ($miners as $miner) {
            $this->assertInstanceOf(Miner::class, $miner);
        }
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetMinersWithName(): void
    {
        $miners = self::$sdk->getMiningApi()->getMinersWithName();
        foreach ($miners as $name => $miner) {
            $this->assertIsString($name);
            $this->assertInstanceOf(Miner::class, $miner);
        }
    }

    public function testGetNewAssetPerBlock(): void
    {
        $assetPerBlock = self::$sdk->getMiningApi()->getNewAssetPerBlock();
        $this->assertIsString($assetPerBlock);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testListMinersRelative(): void
    {
        $minerIds = self::$sdk->getMiningApi()->listMinersRelative();
        foreach ($minerIds as $minerId) {
            $this->assertInstanceOf(MinerId::class, $minerId);
        }
    }

    public function testFindVotedMiners(): void
    {
        /** @var Miner[] $miners */
        $miners = self::$sdk->getMiningApi()->findVotedMiners(['0:0', '0:1']);
        $this->assertEquals('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $miners[0]->getSigningKey());
        $this->assertEquals('1.2.5', $miners[1]->getMinerAccount()->getId());
    }

    public function testFindAllVotingInfo(): void
    {
        /** @var MinerVotingInfo[] $minersInfo */
        $minersInfo = self::$sdk->getMiningApi()->findAllVotingInfo('init', SearchMinerVoting::NAME_DESC, null, DCoreSDKTest::ACCOUNT_NAME_1, true);
        $this->assertTrue($minersInfo[0]->isVoted());
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testCreateVoteOperation(): void
    {
        $minerId = new ChainObject('1.4.4');
        $miners = self::$sdk->getMiningApi()->getMiners([$minerId]);
        /** @var Miner $miner */
        $miner = reset($miners);

        $voteOperation = self::$sdk->getMiningApi()->createVoteOperation(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), [$minerId]);

        $this->assertContains($miner->getVoteId(), $voteOperation->getOptions()->getVotes());
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ObjectNotFoundException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     * @throws \Exception
     */
    public function testVote(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $accountId = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
//        $credentials = new Credentials($accountId, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
//        self::$sdk->getMiningApi()->vote($credentials, [new ChainObject('1.4.4')]);
    }

    public function testCreateMiner(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testUpdateMiner(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testWithdrawVesting(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testVoteForMiner(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSetVotingProxy(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSetDesiredMinerCount(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

}
