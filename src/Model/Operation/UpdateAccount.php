<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Options;
use Exception;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class UpdateAccount extends BaseOperation
{
    public const OPERATION_TYPE = 2;
    public const OPERATION_NAME = 'account_update';

    /** @var ChainObject */
    private $accountId;
    /** @var Authority */
    private $owner;
    /** @var Authority */
    private $active;
    /** @var Options */
    private $options;
    /** @var array */
    private $extensions = [];

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        $this->options = new Options();
        $this->options->setPricePerSubscribe(new AssetAmount());
        $this->owner = new Authority();
        $this->active = new Authority();

        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[account]' => 'accountId',
                '[owner][weight_threshold]' => 'owner.weightThreshold',
                '[owner][key_auths]' => 'owner.keyAuths',
                '[active][key_auths]' => 'active.keyAuths',
                '[new_options][memo_key]' => 'options.memoKey',
                '[new_options][voting_account]' => 'options.votingAccount',
                '[new_options][num_miner]' => 'options.numMiner',
                '[new_options][votes]' => 'options.votes',
                '[new_options][extensions]' => 'options.extensions',
                '[new_options][allow_subscription]' => 'options.allowSubscription',
                '[new_options][price_per_subscribe][amount]' => 'options.pricePerSubscribe.amount',
                '[new_options][price_per_subscribe][asset_id]' => 'options.pricePerSubscribe.assetId',
                '[new_options][subscription_period]' => 'options.subscriptionPeriod',
            ] as $path => $modelPath
        ) {
            try {
                if ($value = $this->getPropertyAccessor()->getValue($rawOperation, $path)) {
                    $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
                }
            } catch (NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getAccountId(): ?ChainObject
    {
        return $this->accountId;
    }

    /**
     * @param ChainObject|string $accountId
     * @return UpdateAccount
     * @throws ValidationException
     */
    public function setAccountId($accountId): UpdateAccount
    {
        if (is_string($accountId)) {
            $accountId = new ChainObject($accountId);
        }

        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return Authority
     */
    public function getOwner(): ?Authority
    {
        return $this->owner;
    }

    /**
     * @param Authority $owner
     * @return UpdateAccount
     */
    public function setOwner(Authority $owner): UpdateAccount
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return Authority
     */
    public function getActive(): ?Authority
    {
        return $this->active;
    }

    /**
     * @param Authority $active
     * @return UpdateAccount
     */
    public function setActive(Authority $active): UpdateAccount
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return Options
     */
    public function getOptions(): ?Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     * @return UpdateAccount
     */
    public function setOptions(Options $options): UpdateAccount
    {
        $this->options = $options;
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
     * @return UpdateAccount
     */
    public function setExtensions(array $extensions): UpdateAccount
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            array_filter([
                'fee' => $this->getFee()->toArray(),
                'account' => $this->getAccountId() ? $this->getAccountId()->getId() : null,
                'owner' => $this->getOwner() ? $this->getOwner()->toArray() : null,
                'active' => $this->getActive() ? $this->getActive()->toArray() : null,
                'new_options' => $this->getOptions() ? $this->getOptions()->toArray() : null,
                'extensions' => [],
            ]),
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getAccountId() ? $this->getAccountId()->toBytes() : '00',
            $this->getOwner() ? str_pad(dechex(count($this->getOwner()->getKeyAuths())), 2, '0', STR_PAD_LEFT) . $this->getOwner()->toBytes() : '00',
            $this->getActive() ? str_pad(dechex(count($this->getActive()->getKeyAuths())), 2, '0', STR_PAD_LEFT) . $this->getActive()->toBytes() : '00',
            $this->getOptions() ? '01' . $this->getOptions()->toBytes() : '00',
            '00',
        ]);
    }
}
