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
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountById;
use DCorePHP\Net\Model\Request\GetAccountsById;
use DCorePHP\Net\Model\Request\GetActualVotes;
use DCorePHP\Net\Model\Request\GetAssetPerBlock;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetMinerByAccount;
use DCorePHP\Net\Model\Request\GetMinerCount;
use DCorePHP\Net\Model\Request\GetMiners;
use DCorePHP\Net\Model\Request\GetNewAssetPerBlock;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Request\LookupMinerAccounts;
use DCorePHP\Net\Model\Request\LookupVoteIds;
use DCorePHP\Net\Model\Request\NetworkBroadcast;
use DCorePHP\Net\Model\Request\SearchMinerVoting;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class MiningApiTest extends DCoreSDKTest
{
    public function testGetActualVotes(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_actual_votes",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetActualVotes::responseToModel(new BaseResponse('{"id":3,"result":[{"account_name":"all-txs","votes":"5116211701483078"},{"account_name":"u46f36fcd24d74ae58c9b0e49a1f0103c","votes":"1489466168173"},{"account_name":"init10","votes":"994298995155"},{"account_name":"init4","votes":"994006270155"},{"account_name":"init3","votes":"793228016266"},{"account_name":"init8","votes":"728268945748"},{"account_name":"init7","votes":"713635753857"},{"account_name":"init0","votes":"509481350337"},{"account_name":"init1","votes":"508749301447"},{"account_name":"init6","votes":"107214008260"},{"account_name":"init5","votes":"25439592617"},{"account_name":"init2","votes":"6498702205"},{"account_name":"init9","votes":"6498702205"},{"account_name":"dont-vote-for-me","votes":0}]}'))
                ));
        }

        $votes = $this->sdk->getMiningApi()->getActualVotes();
        foreach ($votes as $vote) {
            $this->assertInstanceOf(MinerVotes::class, $vote);
        }
    }

    public function testGetAssetPerBlock(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_asset_per_block_by_block_num",["100"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetAssetPerBlock::responseToModel(new BaseResponse('{"id":3,"result":0}'))
                ));
        }

        $asset = $this->sdk->getMiningApi()->getAssetPerBlock('100');
        $this->assertEquals('0', $asset);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetFeedsByMiner(): void
    {
        $feeds = $this->sdk->getMiningApi()->getFeedsByMiner(new ChainObject('1.2.4'));
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetMinerByAccount(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_miner_by_account",["1.2.4"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetMinerByAccount::responseToModel(new BaseResponse('{"id":3,"result":{"id":"1.4.1","miner_account":"1.2.4","last_aslot":10489730,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.6","vote_id":"0:0","total_votes":"509480350335","url":"","total_missed":479000,"last_confirmed_block_num":4433245,"vote_ranking":7}}'))
                ));
        }

        $miner = $this->sdk->getMiningApi()->getMinerByAccount(new ChainObject('1.2.4'));
        $this->assertEquals('1.2.4', $miner->getMinerAccount()->getId());
    }

    public function testGetMinerCount(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_miner_count",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetMinerCount::responseToModel(new BaseResponse('{"id":3,"result":14}'))
                ));
        }

        $count = $this->sdk->getMiningApi()->getMinerCount();
        $this->assertRegExp('/^[0-9]+$/', $count);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetMiners(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_miner_accounts",["",2]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_objects",[["1.4.13","1.4.14"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    LookupMinerAccounts::responseToModel(new BaseResponse('{"id":3,"result":[["all-txs","1.4.13"],["dont-vote-for-me","1.4.14"]]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetMiners::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.4.13","miner_account":"1.2.85","last_aslot":0,"signing_key":"DCT6ZNZ5KGadKr346doCUvUUYu1fTgDoTwEV1aCbrNqgP82oN9ADt","vote_id":"0:12","total_votes":"5116212431583078","url":"my new URL","total_missed":399756,"last_confirmed_block_num":0,"vote_ranking":0},{"id":"1.4.14","miner_account":"1.2.12649","last_aslot":0,"signing_key":"DCT77jKjNhFD9UVf6b9qUazzf2VizxuwSNbmRB8dZbB5V4kcNm229","vote_id":"0:13","total_votes":0,"url":"","total_missed":0,"last_confirmed_block_num":0,"vote_ranking":13}]}'))
                ));
        }

        $minersRelative = $this->sdk->getMiningApi()->listMinersRelative('', 2);
        $minersChainObjects = array_map(function ($minerId) { return $minerId->getId();}, $minersRelative);
        $miners = $this->sdk->getMiningApi()->getMiners($minersChainObjects);

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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(5))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_miner_accounts",["",1000]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_objects",[["1.4.13","1.4.14","1.4.1","1.4.2","1.4.11","1.4.3","1.4.4","1.4.5","1.4.6","1.4.7","1.4.8","1.4.9","1.4.10","1.4.12"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    LookupMinerAccounts::responseToModel(new BaseResponse('{"id":3,"result":[["all-txs","1.4.13"],["dont-vote-for-me","1.4.14"],["init0","1.4.1"],["init1","1.4.2"],["init10","1.4.11"],["init2","1.4.3"],["init3","1.4.4"],["init4","1.4.5"],["init5","1.4.6"],["init6","1.4.7"],["init7","1.4.8"],["init8","1.4.9"],["init9","1.4.10"],["u46f36fcd24d74ae58c9b0e49a1f0103c","1.4.12"]]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetMiners::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.4.13","miner_account":"1.2.85","last_aslot":0,"signing_key":"DCT6ZNZ5KGadKr346doCUvUUYu1fTgDoTwEV1aCbrNqgP82oN9ADt","vote_id":"0:12","total_votes":"5116212531083078","url":"my new URL","total_missed":405615,"last_confirmed_block_num":0,"vote_ranking":0},{"id":"1.4.14","miner_account":"1.2.12649","last_aslot":0,"signing_key":"DCT77jKjNhFD9UVf6b9qUazzf2VizxuwSNbmRB8dZbB5V4kcNm229","vote_id":"0:13","total_votes":0,"url":"","total_missed":0,"last_confirmed_block_num":0,"vote_ranking":13},{"id":"1.4.1","miner_account":"1.2.4","last_aslot":10573595,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.6","vote_id":"0:0","total_votes":"509468350330","url":"","total_missed":479000,"last_confirmed_block_num":4502085,"vote_ranking":7},{"id":"1.4.2","miner_account":"1.2.5","last_aslot":10573591,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.4","vote_id":"0:1","total_votes":"508736301440","url":"","total_missed":479002,"last_confirmed_block_num":4502081,"vote_ranking":8},{"id":"1.4.11","miner_account":"1.2.14","last_aslot":10573581,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.9","vote_id":"0:10","total_votes":"993965435159","url":"","total_missed":477512,"last_confirmed_block_num":4502073,"vote_ranking":2},{"id":"1.4.3","miner_account":"1.2.6","last_aslot":8407605,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.2","vote_id":"0:2","total_votes":"6498702205","url":"","total_missed":474559,"last_confirmed_block_num":2723982,"vote_ranking":11},{"id":"1.4.4","miner_account":"1.2.7","last_aslot":10573583,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.7","vote_id":"0:3","total_votes":"793228016266","url":"","total_missed":478522,"last_confirmed_block_num":4502075,"vote_ranking":4},{"id":"1.4.5","miner_account":"1.2.8","last_aslot":10573592,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.0","vote_id":"0:4","total_votes":"993672710159","url":"","total_missed":479001,"last_confirmed_block_num":4502082,"vote_ranking":3},{"id":"1.4.6","miner_account":"1.2.9","last_aslot":10573580,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.3","vote_id":"0:5","total_votes":"25245546611","url":"","total_missed":478966,"last_confirmed_block_num":4502072,"vote_ranking":10},{"id":"1.4.7","miner_account":"1.2.10","last_aslot":10573593,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.10","vote_id":"0:6","total_votes":"107214008260","url":"","total_missed":477313,"last_confirmed_block_num":4502083,"vote_ranking":9},{"id":"1.4.8","miner_account":"1.2.11","last_aslot":10573582,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.5","vote_id":"0:7","total_votes":"713289193854","url":"","total_missed":479005,"last_confirmed_block_num":4502074,"vote_ranking":6},{"id":"1.4.9","miner_account":"1.2.12","last_aslot":10573594,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.8","vote_id":"0:8","total_votes":"727728339739","url":"","total_missed":477819,"last_confirmed_block_num":4502084,"vote_ranking":5},{"id":"1.4.10","miner_account":"1.2.13","last_aslot":6247981,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.1","vote_id":"0:9","total_votes":"6498702205","url":"","total_missed":5941,"last_confirmed_block_num":954015,"vote_ranking":12},{"id":"1.4.12","miner_account":"1.2.27","last_aslot":0,"signing_key":"DCT8cYDtKZvcAyWfFRusy6ja1Hafe9Ys4UPJS92ajTmcrufHnGgjp","vote_id":"0:11","total_votes":"1489119608170","url":"http://ardstudio.studenthosting.sk","total_missed":400267,"last_confirmed_block_num":0,"vote_ranking":1}]}'))
                ));
        }

        $miners = $this->sdk->getMiningApi()->getMinersWithName();
        foreach ($miners as $name => $miner) {
            $this->assertIsString($name);
            $this->assertInstanceOf(Miner::class, $miner);
        }
    }

    public function testGetNewAssetPerBlock(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_new_asset_per_block",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetNewAssetPerBlock::responseToModel(new BaseResponse('{"id":3,"result":37000000}'))
                ));
        }

        $assetPerBlock = $this->sdk->getMiningApi()->getNewAssetPerBlock();
        $this->assertIsString($assetPerBlock);
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testListMinersRelative(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_miner_accounts",["",1000]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    LookupMinerAccounts::responseToModel(new BaseResponse('{"id":3,"result":[["all-txs","1.4.13"],["dont-vote-for-me","1.4.14"],["init0","1.4.1"],["init1","1.4.2"],["init10","1.4.11"],["init2","1.4.3"],["init3","1.4.4"],["init4","1.4.5"],["init5","1.4.6"],["init6","1.4.7"],["init7","1.4.8"],["init8","1.4.9"],["init9","1.4.10"],["u46f36fcd24d74ae58c9b0e49a1f0103c","1.4.12"]]}'))
                ));
        }

        $minerIds = $this->sdk->getMiningApi()->listMinersRelative();
        foreach ($minerIds as $minerId) {
            $this->assertInstanceOf(MinerId::class, $minerId);
        }
    }

    public function testFindVotedMiners(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"lookup_vote_ids",[["0:0","0:1"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    LookupVoteIds::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.4.1","miner_account":"1.2.4","last_aslot":10573977,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.6","vote_id":"0:0","total_votes":"509468350330","url":"","total_missed":479000,"last_confirmed_block_num":4502399,"vote_ranking":7},{"id":"1.4.2","miner_account":"1.2.5","last_aslot":10573974,"signing_key":"DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4","pay_vb":"1.9.4","vote_id":"0:1","total_votes":"508736301440","url":"","total_missed":479002,"last_confirmed_block_num":4502397,"vote_ranking":8}]}'))
                ));
        }

        /** @var Miner[] $miners */
        $miners = $this->sdk->getMiningApi()->findVotedMiners(['0:0', '0:1']);
        $this->assertEquals('DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF', $miners[0]->getSigningKey());
        $this->assertEquals('1.2.5', $miners[1]->getMinerAccount()->getId());
    }

    public function testFindAllVotingInfo(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"search_miner_voting",["public-account-9","init",true,"-name",null,1000]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    SearchMinerVoting::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.4.4","name":"init3","url":"","total_votes":"1999034598065","voted":true}]}'))
                ));
        }

        /** @var MinerVotingInfo[] $minersInfo */
        $minersInfo = $this->sdk->getMiningApi()->findAllVotingInfo('init', SearchMinerVoting::NAME_DESC, null, DCoreSDKTest::ACCOUNT_NAME_1, true);
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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(7))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_objects",[["1.4.4"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_objects",[["1.4.4"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_accounts",[["1.2.27"]]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetMiners::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.4.4","miner_account":"1.2.7","last_aslot":1067703,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.4","vote_id":"0:3","total_votes":"1999034598065","url":"","total_missed":36110,"last_confirmed_block_num":670484,"vote_ranking":0}]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetMiners::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.4.4","miner_account":"1.2.7","last_aslot":1067703,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.4","vote_id":"0:3","total_votes":"1999034598065","url":"","total_missed":36110,"last_confirmed_block_num":670484,"vote_ranking":0}]}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":7,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":0,"asset_id":"1.3.0"},"subscription_period":0},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}'))
                ));
        }

        $minerId = new ChainObject('1.4.4');
        $miners = $this->sdk->getMiningApi()->getMiners([$minerId]);
        /** @var Miner $miner */
        $miner = reset($miners);

        $voteOperation = $this->sdk->getMiningApi()->createVoteOperation(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), [$minerId]);

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
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(13))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_objects",[["1.4.4"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(4)->toJson() === '{"jsonrpc":"2.0","id":4,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(5)->toJson() === '{"jsonrpc":"2.0","id":5,"method":"call","params":[6,"get_accounts",[["1.2.27"]]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(6)->toJson() === '{"jsonrpc":"2.0","id":6,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(7)->toJson() === '{"jsonrpc":"2.0","id":7,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(8)->toJson() === '{"jsonrpc":"2.0","id":8,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(9)->toJson() === '{"jsonrpc":"2.0","id":9,"method":"call","params":[6,"get_chain_id",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(10)->toJson() === '{"jsonrpc":"2.0","id":10,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(11)->toJson() === '{"jsonrpc":"2.0","id":11,"method":"call","params":[6,"get_required_fees",[[[2,{"fee":{"amount":0,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1}}]],"1.3.0"]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(12)->toJson() === '{"jsonrpc":"2.0","id":12,"method":"call","params":[1,"network_broadcast",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(13)->toJson() === '{"jsonrpc":"2.0","id":13,"method":"call","params":[7,"broadcast_transaction_with_callback",[6,{"extensions":[],"operations":[[2,{"fee":{"amount":100000,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1}}]],"ref_block_num":7053,"ref_block_prefix":"3553964681","expiration":"2019-05-16T14:02:53","signatures":["1f1b3c885b1d66d70bd74b3bdcdfacc787869c2334c8061217cd8266b3c7ef1f2d67dc1c1c9d1f0d51f003b1ccaecb9d998606b184f2de8b5018093f4ec8993a65"]}]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetMiners::responseToModel(new BaseResponse('{"id":3,"result":[{"id":"1.4.4","miner_account":"1.2.7","last_aslot":1469424,"signing_key":"DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF","pay_vb":"1.9.4","vote_id":"0:3","total_votes":"1770992184027","url":"","total_missed":36110,"last_confirmed_block_num":1055688,"vote_ranking":0}]}')),
                    Database::responseToModel(new BaseResponse('{"id":4,"result":6}')),
                    GetAccountById::responseToModel(new BaseResponse('{"id":5,"result":[{"id":"1.2.27","registrar":"1.2.2","name":"public-account-9","owner":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT51ojM7TUGVpFNUJWX8wi5dYp4iA4brRG16zWfcteVZRZHnkWCF",1]]},"active":{"weight_threshold":1,"account_auths":[],"key_auths":[["DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb",1]]},"options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1},"rights_to_publish":{"is_publishing_manager":false,"publishing_rights_received":[],"publishing_rights_forwarded":[]},"statistics":"2.5.27","top_n_control_flags":0}]}')),
                    Database::responseToModel(new BaseResponse('{"id":6,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":7,"result":{"id":"2.1.0","head_block_number":1055629,"head_block_id":"00101b8d8932d5d327d0f746b3c90a97049d45ff","time":"2019-05-16T14:02:20","current_miner":"1.4.9","next_maintenance_time":"2019-05-17T00:00:00","last_budget_time":"2019-05-16T00:00:00","unspent_fee_budget":20753347,"mined_rewards":"340141000000","miner_budget_from_fees":44333392,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":18,"recently_missed_count":0,"current_aslot":1469359,"recent_slots_filled":"255170234279294010610653414152504081919","dynamic_flags":0,"last_irreversible_block_num":1055629}}')),
                    Database::responseToModel(new BaseResponse('{"id":8,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":9,"result":"a76a2db75f7a8018d41f2d648c766fdb0ddc79ac77104d243074ebdd5186bfbe"}')),
                    Database::responseToModel(new BaseResponse('{"id":10,"result":6}')),
                    GetRequiredFees::responseToModel(new BaseResponse('{"id":11,"result":[{"amount":100000,"asset_id":"1.3.0"}]}')),
                    NetworkBroadcast::responseToModel(new BaseResponse('{"id":12,"result":7}')),
                    BroadcastTransactionWithCallback::responseToModel(new BaseResponse('{"method":"notice","params":[6,[{"id":"2c965391e6837c9b7ebb2f5e4b1e4cc1f8fdcf5e","block_num":1055630,"trx_num":0,"trx":{"ref_block_num":7053,"ref_block_prefix":3553964681,"expiration":"2019-05-16T14:02:53","operations":[[2,{"fee":{"amount":100000,"asset_id":"1.3.0"},"account":"1.2.27","new_options":{"memo_key":"DCT6TjLhr8uESvgtxrbWuXNAN3vcqzBMw5eyEup3PMiD2gnVxeuTb","voting_account":"1.2.3","num_miner":0,"votes":["0:3"],"extensions":[],"allow_subscription":false,"price_per_subscribe":{"amount":1,"asset_id":"1.3.0"},"subscription_period":1},"extensions":{}}]],"extensions":[],"signatures":["1f1b3c885b1d66d70bd74b3bdcdfacc787869c2334c8061217cd8266b3c7ef1f2d67dc1c1c9d1f0d51f003b1ccaecb9d998606b184f2de8b5018093f4ec8993a65"],"operation_results":[[0,{}]]}}]]}'))
                ));
        }

        $accountId = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
        $credentials = new Credentials($accountId, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $this->sdk->getMiningApi()->vote($credentials, [new ChainObject('1.4.4')]);


        if (!$this->websocketMock) {
            $this->expectNotToPerformAssertions();
        }
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
