<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Options;

class AccountUpdate extends BaseOperation
{
    public const OPERATION_TYPE = 2;
    public const OPERATION_NAME = 'account_update';

    /** @var ChainObject */
    private $account;
    /** @var Options */
    private $newOptions;
    /** @var array */
    private $extensions = [];

    public function __construct()
    {
        parent::__construct();
        $this->newOptions = new Options();
    }

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[account]' => 'account',
                '[new_options][memo_key]' => 'newOptions.memoKey',
                '[new_options][voting_account]' => 'newOptions.votingAccount',
                '[new_options][num_miner]' => 'newOptions.numMiner',
                '[new_options][votes]' => 'newOptions.votes',
                '[new_options][extensions]' => 'newOptions.extensions',
                '[new_options][allow_subscription]' => 'newOptions.allowSubscription',
                '[new_options][price_per_subscribe][amount]' => 'newOptions.pricePerSubscribe.amount',
                '[new_options][price_per_subscribe][asset_id]' => 'newOptions.pricePerSubscribe.assetId',
                '[new_options][subscription_period]' => 'newOptions.subscriptionPeriod',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getAccount(): ?ChainObject
    {
        return $this->account;
    }

    /**
     * @param ChainObject|string $account
     * @return AccountUpdate
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAccount($account): AccountUpdate
    {
        if (is_string($account)) {
            $account = new ChainObject($account);
        }

        $this->account = $account;
        return $this;
    }

    /**
     * @return Options
     */
    public function getNewOptions(): Options
    {
        return $this->newOptions;
    }

    /**
     * @param Options $newOptions
     * @return AccountUpdate
     */
    public function setNewOptions(Options $newOptions): AccountUpdate
    {
        $this->newOptions = $newOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return AccountUpdate
     */
    public function setExtensions(array $extensions): AccountUpdate
    {
        $this->extensions = $extensions;
        return $this;
    }
}
