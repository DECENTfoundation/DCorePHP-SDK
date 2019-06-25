<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\Options;
use DCorePHP\Model\Publishing;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetAccount extends BaseRequest
{
    /**
     * @param BaseResponse $response
     * @return Account
     */
    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult() ? self::resultToModel($response->getResult()) : null;
    }

    /**
     * @param array $result
     * @return Account
     */
    protected static function resultToModel(array $result): Account
    {
        $account = new Account();
        $account
            ->setOwner(new Authority())
            ->setActive(new Authority())
            ->setOptions((new Options())->setPricePerSubscribe(new AssetAmount()))
            ->setRightsToPublish(new Publishing());

        foreach (
            [
                '[id]' => 'id',
                '[registrar]' => 'registrar',
                '[name]' => 'name',
                '[owner][weight_threshold]' => 'owner.weightThreshold',
                '[owner][account_auths]' => 'owner.accountAuths',
                '[owner][key_auths]' => 'owner.keyAuths',
                '[active][weight_threshold]' => 'active.weightThreshold',
                '[active][account_auths]' => 'active.accountAuths',
                '[active][key_auths]' => 'active.keyAuths',
                '[options][memo_key]' => 'options.memoKey',
                '[options][voting_account]' => 'options.votingAccount',
                '[options][num_miner]' => 'options.numMiner',
                '[options][votes]' => 'options.votes',
                '[options][extensions]' => 'options.extensions',
                '[options][allow_subscription]' => 'options.allowSubscription',
                '[options][price_per_subscribe][amount]' => 'options.pricePerSubscribe.amount',
                '[options][price_per_subscribe][asset_id]' => 'options.pricePerSubscribe.assetId',
                '[options][subscription_period]' => 'options.subscriptionPeriod',
                '[rights_to_publish][is_publishing_manager]' => 'rightsToPublish.isPublishingManager',
                '[rights_to_publish][publishing_rights_received]' => 'rightsToPublish.publishRightsReceived',
                '[rights_to_publish][publishing_rights_forwarded]' => 'rightsToPublish.publishRightsForwarded',
                '[statistics]' => 'statistics',
                '[top_n_control_flags]' => 'topControlFlags',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($account, $modelPath, $value);
        }

        return $account;
    }
}
