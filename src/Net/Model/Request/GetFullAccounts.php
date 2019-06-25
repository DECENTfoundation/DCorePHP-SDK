<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\AccountBalance;
use DCorePHP\Model\Explorer\Miner;
use DCorePHP\Model\FullAccount;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetFullAccounts extends BaseRequest
{
    public function __construct(array $namesOrIds, bool $subscribe)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_full_accounts',
            [$namesOrIds, $subscribe]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $accounts = [];
        foreach ($response->getResult() as [$nameOrId, $rawAccount]) {
            $fullAccount = new FullAccount();
            foreach (
                [
                    '[account][id]' => 'account.id',
                    '[account][registrar]' => 'account.registrar',
                    '[account][name]' => 'account.name',
                    '[account][owner][weight_threshold]' => 'account.owner.weight_threshold',
                    '[account][owner][account_auths]' => 'account.owner.accountAuths',
                    '[account][owner][key_auths]' => 'account.owner.keyAuths',
                    '[account][active][weight_threshold]' => 'account.active.weightThreshold',
                    '[account][active][account_auths]' => 'account.active.accountAuths',
                    '[account][active][key_auths]' => 'account.active.keyAuths',
                    '[account][options][memo_key]' => 'account.options.memoKey',
                    '[account][options][voting_account]' => 'account.options.votingAccount',
                    '[account][options][num_miner]' => 'account.options.numMiner',
                    '[account][options][votes]' => 'account.options.votes',
                    '[account][options][extensions]' => 'account.options.extensions',
                    '[account][options][allow_subscription]' => 'account.options.allow_subscription',
                    '[account][options][price_per_subscribe][amount]' => 'account.options.pricePerSubscribe.amount',
                    '[account][options][price_per_subscribe][asset_id]' => 'account.options.pricePerSubscribe.assetId',
                    '[account][options][subscription_period]' => 'account.options.subscriptionPeriod',
                    '[account][rights_to_publish][is_publishing_manager]' => 'account.rightsToPublish.isPublishingManager',
                    '[account][rights_to_publish][publishing_rights_received]' => 'account.rightsToPublish.publishRightsReceived',
                    '[account][rights_to_publish][publishing_rights_forwarded]' => 'account.rightsToPublish.publishRightsForwarded',
                    '[account][statistics]' => 'account.statistics',
                    '[account][top_n_control_flags]' => 'account.topControlFlags',
                    '[statistics][id]' => 'statistics.id',
                    '[statistics][owner]' => 'statistics.owner',
                    '[statistics][most_recent_op]' => 'statistics.mostRecentOp',
                    '[statistics][total_ops]' => 'statistics.totalOps',
                    '[statistics][total_core_in_orders]' => 'statistics.totalCoreInOrders',
                    '[statistics][pending_fees]' => 'statistics.pendingFees',
                    '[statistics][pending_vested_fees]' => 'statistics.pendingVestedFees',
                    '[registrar_name]' => 'registrarName',
                    '[vesting_balances]' => 'vestingBalances',
                    '[proposals]' => 'proposals'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawAccount, $path);
                self::getPropertyAccessor()->setValue($fullAccount, $modelPath, $value);
            }

            $votes = [];
            foreach ($rawAccount['votes'] as $rawVote) {
                $vote = new Miner();
                foreach (
                    [
                        '[id]' => 'id',
                        '[miner_account]' => 'minerAccount',
                        '[last_aslot]' => 'lastAslot',
                        '[signing_key]' => 'signingKey',
                        '[pay_vb]' => 'payVb',
                        '[vote_id]' => 'voteId',
                        '[total_votes]' => 'totalVotes',
                        '[url]' => 'url',
                        '[total_missed]' => 'totalMissed',
                        '[last_confirmed_block_num]' => 'lastConfirmedBlockNum',
                        '[vote_ranking]' => 'vote_ranking',
                    ] as $path => $modelPath
                ) {
                    $value = self::getPropertyAccessor()->getValue($rawVote, $path);
                    self::getPropertyAccessor()->setValue($vote, $modelPath, $value);
                }
                $votes[] = $vote;
            }
            $fullAccount->setVotes($votes);

            $balances = [];
            foreach ($rawAccount['balances'] as $rawVote) {
                $balance = new AccountBalance();
                foreach (
                    [
                        '[id]' => 'id',
                        '[owner]' => 'owner',
                        '[asset_type]' => 'assetType',
                        '[balance]' => 'balance'
                    ] as $path => $modelPath
                ) {
                    $value = self::getPropertyAccessor()->getValue($rawVote, $path);
                    self::getPropertyAccessor()->setValue($balance, $modelPath, $value);
                }
                $balances[] = $balance;
            }
            $fullAccount->setBalances($balances);

            $accounts[$nameOrId] = $fullAccount;
        }
        return $accounts;
    }
}