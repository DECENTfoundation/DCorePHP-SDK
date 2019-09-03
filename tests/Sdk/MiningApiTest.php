<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\MinerVotes;
use DCorePHP\Model\Mining\MinerId;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Mining\MinerVotingInfo;
use DCorePHP\Net\Model\Request\SearchMinerVoting;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class MiningApiTest extends DCoreSDKTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::testVote();
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     * @doesNotPerformAssertions
     */
    public static function testVote(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getMiningApi()->vote($credentials, [new ChainObject('1.4.4')]);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetActualVotes(): void
    {
        $votes = self::$sdk->getMiningApi()->getActualVotes();
        foreach ($votes as $vote) {
            $this->assertInstanceOf(MinerVotes::class, $vote);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetAssetPerBlock(): void
    {
        $asset = self::$sdk->getMiningApi()->getAssetPerBlock('100');
        $this->assertEquals('0', $asset);
    }

    public function testGetFeedsByMiner(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetMinerByAccount(): void
    {
        $miner = self::$sdk->getMiningApi()->getMinerByAccount(new ChainObject('1.2.4'));
        $this->assertEquals('1.2.4', $miner->getMinerAccount()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetMinerCount(): void
    {
        $count = self::$sdk->getMiningApi()->getMinerCount();
        $this->assertRegExp('/^[0-9]+$/', $count);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetMiners(): void
    {
        $minersRelative = self::$sdk->getMiningApi()->listMinersRelative('', 2);
        $minersChainObjects = array_map(static function ($minerId) { return $minerId->getId();}, $minersRelative);
        $miners = self::$sdk->getMiningApi()->getMiners($minersChainObjects);

        $this->assertEquals(2, sizeof($miners));
        foreach ($miners as $miner) {
            $this->assertInstanceOf(Miner::class, $miner);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetMinersWithName(): void
    {
        $miners = self::$sdk->getMiningApi()->getMinersWithName();
        foreach ($miners as $name => $miner) {
            $this->assertIsString($name);
            $this->assertInstanceOf(Miner::class, $miner);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testGetNewAssetPerBlock(): void
    {
        $assetPerBlock = self::$sdk->getMiningApi()->getNewAssetPerBlock();
        $this->assertIsString($assetPerBlock);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testListMinersRelative(): void
    {
        $minerIds = self::$sdk->getMiningApi()->listMinersRelative();
        foreach ($minerIds as $minerId) {
            $this->assertInstanceOf(MinerId::class, $minerId);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testFindVotedMiners(): void
    {
        /** @var Miner[] $miners */
        $miners = self::$sdk->getMiningApi()->findVotedMiners(['0:0', '0:1']);
        $this->assertEquals('DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy', $miners[0]->getSigningKey());
        $this->assertEquals('1.2.5', $miners[1]->getMinerAccount()->getId());
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function testFindAllVotingInfo(): void
    {
        /** @var MinerVotingInfo[] $minersInfo */
        $minersInfo = self::$sdk->getMiningApi()->findAllVotingInfo('init', SearchMinerVoting::NAME_DESC, null, DCoreSDKTest::ACCOUNT_NAME_1, true);
        $this->assertTrue($minersInfo[0]->isVoted());
    }

    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
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
}
