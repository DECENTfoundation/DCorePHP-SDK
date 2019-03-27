<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\DCoreApi;
use DCorePHP\Model\General\ChainProperty;
use DCorePHP\Model\General\GlobalProperty;
use DCorePHP\Model\General\MinerRewardInput;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetChainProperties;
use DCorePHP\Net\Model\Request\GetConfig;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetGlobalProperties;
use DCorePHP\Net\Model\Request\GetTimeToMaintenance;
use DCorePHP\Net\Model\Request\Info;
use DCorePHP\Net\Model\Request\Login;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class GeneralApiTest extends DCoreSDKTest
{
    public function testGetInfo(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"info",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    Info::responseToModel(new BaseResponse('{"id":3,"result":"database_api"}'))
                ));
        }

        $info = $this->sdk->getGeneralApi()->info();
        $this->assertEquals('database_api', $info);
    }

    public function testGetChainProps(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_chain_properties",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetChainProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.9.0","chain_id":"17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc","immutable_parameters":{"min_miner_count":11,"num_special_accounts":0,"num_special_assets":0}}}'))
                ));
        }

        $chainProps = $this->sdk->getGeneralApi()->getChainProperties();

        $this->assertInstanceOf(ChainProperty::class, $chainProps);
        $this->assertEquals('2.9.0', $chainProps->getId()->getId());
        $this->assertEquals('17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc', $chainProps->getChainId());
    }

    public function testGetGlobalProps(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_global_properties",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.0.0","parameters":{"current_fees":{"parameters":[[0,{"fee":500000}],[1,{"basic_fee":500000}],[2,{"fee":500000}],[3,{"basic_fee":500000}],[4,{"fee":500000}],[5,{"fee":10}],[6,{"fee":50000000}],[7,{"fee":500000}],[8,{"fee":10}],[9,{"fee":500000,"price_per_kbyte":10}],[10,{"fee":500000,"price_per_kbyte":10}],[11,{"fee":500000}],[12,{"fee":500000}],[13,{"fee":500000}],[14,{"fee":500000,"price_per_kbyte":10}],[15,{"fee":0}],[16,{"fee":500000}],[17,{"fee":500000}],[18,{"fee":500000,"price_per_kbyte":10}],[19,{"fee":5000000}],[20,{"fee":0}],[21,{"fee":0}],[22,{"fee":0}],[23,{"fee":0}],[24,{"fee":0}],[25,{"fee":0}],[26,{"fee":0}],[27,{"fee":0}],[28,{"fee":0}],[29,{"fee":0}],[30,{"fee":0}],[31,{"fee":0}],[32,{"fee":0}],[33,{"fee":0}],[34,{"fee":0}],[35,{"fee":0}],[36,{"fee":0}],[37,{"fee":0}],[38,{"fee":0}],[39,{"fee":500000}]],"scale":10000},"block_interval":5,"maintenance_interval":86400,"maintenance_skip_slots":3,"miner_proposal_review_period":1209600,"maximum_transaction_size":4096,"maximum_block_size":2048000,"maximum_time_until_expiration":86400,"maximum_proposal_lifetime":2419200,"maximum_asset_feed_publishers":10,"maximum_miner_count":1001,"maximum_authority_membership":10,"cashback_vesting_period_seconds":31536000,"cashback_vesting_threshold":1000000000,"max_predicate_opcode":1,"max_authority_depth":2,"extensions":[]},"next_available_vote_id":13,"active_miners":["1.4.13","1.4.12","1.4.11","1.4.5","1.4.4","1.4.9","1.4.8","1.4.1","1.4.2","1.4.7","1.4.6"]}}'))
                ));
        }

        $globalProps = $this->sdk->getGeneralApi()->getGlobalProperties();
        
        $this->assertInstanceOf(GlobalProperty::class, $globalProps);
        $this->assertEquals('2.0.0', $globalProps->getId());
        $this->assertEquals('2048000', $globalProps->getParameters()->getMaximumBlockSize());
        $this->assertEquals(1001, $globalProps->getParameters()->getMaximumMinerCount());
    }

    public function testGetConfig(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_config",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetConfig::responseToModel(new BaseResponse('{"id":3,"result":{"GRAPHENE_SYMBOL":"DCT","GRAPHENE_ADDRESS_PREFIX":"DCT","GRAPHENE_MIN_ACCOUNT_NAME_LENGTH":5,"GRAPHENE_MAX_ACCOUNT_NAME_LENGTH":63,"GRAPHENE_MIN_ASSET_SYMBOL_LENGTH":3,"GRAPHENE_MAX_ASSET_SYMBOL_LENGTH":16,"GRAPHENE_MAX_SHARE_SUPPLY":"7319777577456890","GRAPHENE_MAX_PAY_RATE":10000,"GRAPHENE_MAX_SIG_CHECK_DEPTH":2,"GRAPHENE_MIN_TRANSACTION_SIZE_LIMIT":1024,"GRAPHENE_MIN_BLOCK_INTERVAL":1,"GRAPHENE_MAX_BLOCK_INTERVAL":30,"GRAPHENE_DEFAULT_BLOCK_INTERVAL":5,"GRAPHENE_DEFAULT_MAX_TRANSACTION_SIZE":4096,"GRAPHENE_DEFAULT_MAX_BLOCK_SIZE":4096000,"GRAPHENE_DEFAULT_MAX_TIME_UNTIL_EXPIRATION":86400,"GRAPHENE_DEFAULT_MAINTENANCE_INTERVAL":86400,"GRAPHENE_DEFAULT_MAINTENANCE_SKIP_SLOTS":3,"GRAPHENE_MIN_UNDO_HISTORY":10,"GRAPHENE_MAX_UNDO_HISTORY":10000,"GRAPHENE_MIN_BLOCK_SIZE_LIMIT":5120,"GRAPHENE_MIN_TRANSACTION_EXPIRATION_LIMIT":150,"GRAPHENE_BLOCKCHAIN_PRECISION":100000000,"GRAPHENE_BLOCKCHAIN_PRECISION_DIGITS":8,"GRAPHENE_DEFAULT_TRANSFER_FEE":1000000,"GRAPHENE_MAX_INSTANCE_ID":"281474976710655","GRAPHENE_100_PERCENT":10000,"GRAPHENE_1_PERCENT":100,"GRAPHENE_MAX_MARKET_FEE_PERCENT":10000,"GRAPHENE_DEFAULT_FORCE_SETTLEMENT_DELAY":86400,"GRAPHENE_DEFAULT_FORCE_SETTLEMENT_OFFSET":0,"GRAPHENE_DEFAULT_FORCE_SETTLEMENT_MAX_VOLUME":2000,"GRAPHENE_DEFAULT_PRICE_FEED_LIFETIME":86400,"GRAPHENE_MAX_FEED_PRODUCERS":200,"GRAPHENE_DEFAULT_MAX_AUTHORITY_MEMBERSHIP":10,"GRAPHENE_DEFAULT_MAX_ASSET_WHITELIST_AUTHORITIES":10,"GRAPHENE_DEFAULT_MAX_ASSET_FEED_PUBLISHERS":10,"GRAPHENE_COLLATERAL_RATIO_DENOM":1000,"GRAPHENE_MIN_COLLATERAL_RATIO":1001,"GRAPHENE_MAX_COLLATERAL_RATIO":32000,"GRAPHENE_DEFAULT_MAINTENANCE_COLLATERAL_RATIO":1750,"GRAPHENE_DEFAULT_MAX_SHORT_SQUEEZE_RATIO":1500,"GRAPHENE_DEFAULT_MARGIN_PERIOD_SEC":2592000,"GRAPHENE_DEFAULT_MAX_MINERS":1001,"GRAPHENE_DEFAULT_MAX_PROPOSAL_LIFETIME_SEC":2419200,"GRAPHENE_DEFAULT_MINER_PROPOSAL_REVIEW_PERIOD_SEC":1209600,"GRAPHENE_DEFAULT_NETWORK_PERCENT_OF_FEE":2000,"GRAPHENE_DEFAULT_LIFETIME_REFERRER_PERCENT_OF_FEE":3000,"GRAPHENE_DEFAULT_MAX_BULK_DISCOUNT_PERCENT":5000,"GRAPHENE_DEFAULT_BULK_DISCOUNT_THRESHOLD_MIN":"10000000000","GRAPHENE_DEFAULT_BULK_DISCOUNT_THRESHOLD_MAX":"100000000000","GRAPHENE_DEFAULT_CASHBACK_VESTING_PERIOD_SEC":31536000,"GRAPHENE_DEFAULT_CASHBACK_VESTING_THRESHOLD":"10000000000","GRAPHENE_DEFAULT_BURN_PERCENT_OF_FEE":2000,"GRAPHENE_MINER_PAY_PERCENT_PRECISION":1000000000,"GRAPHENE_DEFAULT_MAX_ASSERT_OPCODE":1,"GRAPHENE_DEFAULT_FEE_LIQUIDATION_THRESHOLD":100000000,"GRAPHENE_DEFAULT_ACCOUNTS_PER_FEE_SCALE":1000,"GRAPHENE_DEFAULT_ACCOUNT_FEE_SCALE_BITSHIFTS":4,"GRAPHENE_MAX_WORKER_NAME_LENGTH":63,"GRAPHENE_MAX_URL_LENGTH":127,"GRAPHENE_NEAR_SCHEDULE_CTR_IV":"7640891576956012808","GRAPHENE_FAR_SCHEDULE_CTR_IV":"13503953896175478587","GRAPHENE_CORE_ASSET_CYCLE_RATE":17,"GRAPHENE_CORE_ASSET_CYCLE_RATE_BITS":32,"GRAPHENE_DEFAULT_MINER_PAY_PER_BLOCK":10000000,"GRAPHENE_DEFAULT_MINER_PAY_VESTING_SECONDS":86400,"GRAPHENE_MAX_INTEREST_APR":10000,"GRAPHENE_MINER_ACCOUNT":"1.2.0","GRAPHENE_NULL_ACCOUNT":"1.2.1","GRAPHENE_TEMP_ACCOUNT":"1.2.2"}}'))
                ));
        }

        $config = $this->sdk->getGeneralApi()->getConfig();
        $this->assertEquals('DCT', $config->getGrapheneSymbol());
        $this->assertEquals('DCT', $config->getGrapheneAddressPrefix());
    }

    public function testChainId(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_chain_id",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetChainId::responseToModel(new BaseResponse('{"id":3,"result":"17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc"}'))
                ));
        }

        $chainId = $this->sdk->getGeneralApi()->getChainId();
        $this->assertEquals('17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc', $chainId);
    }

    public function testGetDynamicProperties(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_dynamic_global_properties",[]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetDynamicGlobalProperties::responseToModel(new BaseResponse('{"id":3,"result":{"id":"2.1.0","head_block_number":4447879,"head_block_id":"0043de87122a40b72c6010dc0724bf17a85580be","time":"2019-02-28T13:05:10","current_miner":"1.4.7","next_maintenance_time":"2019-03-01T00:00:00","last_budget_time":"2019-02-28T00:00:00","unspent_fee_budget":37429205,"mined_rewards":"286565000000","miner_budget_from_fees":67828330,"miner_budget_from_rewards":"639249000000","accounts_registered_this_interval":2,"recently_missed_count":0,"current_aslot":10507538,"recent_slots_filled":"297642859398663905703015921329038667775","dynamic_flags":0,"last_irreversible_block_num":4447879}}'))
                ));
        }

        $dynamicGlobalProps = $this->sdk->getGeneralApi()->getDynamicGlobalProperties();
        $this->assertEquals('2.1.0', $dynamicGlobalProps->getId());
    }

    /**
     * @throws \Exception
     */
    public function testTimeToMaintenance(): void
    {
        if ($this->websocketMock) {
            $this->websocketMock
                ->expects($this->exactly(3))
                ->method('send')
                ->withConsecutive(
                    [$this->callback(function(BaseRequest $req) { return $req->setId(1)->toJson() === '{"jsonrpc":"2.0","id":1,"method":"call","params":[1,"login",["",""]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(2)->toJson() === '{"jsonrpc":"2.0","id":2,"method":"call","params":[1,"database",[]]}'; })],
                    [$this->callback(function(BaseRequest $req) { return $req->setId(3)->toJson() === '{"jsonrpc":"2.0","id":3,"method":"call","params":[6,"get_time_to_maint_by_block_time",["2018-10-13T22:26:02.825"]]}'; })]
                )
                ->will($this->onConsecutiveCalls(
                    Login::responseToModel(new BaseResponse('{"id":1,"result":true}')),
                    Database::responseToModel(new BaseResponse('{"id":2,"result":6}')),
                    GetTimeToMaintenance::responseToModel(new BaseResponse('{"id":3,"result":{"time_to_maint":5638,"from_accumulated_fees":1254865054,"block_interval":5}}'))
                ));
        }

        $minerReward = $this->sdk->getGeneralApi()->getTimeToMaintenance('2018-10-13T22:26:02.825');
        $this->assertInstanceOf(MinerRewardInput::class, $minerReward);
    }

}