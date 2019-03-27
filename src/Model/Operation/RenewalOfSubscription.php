<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class RenewalOfSubscription extends BaseOperation
{
    public const OPERATION_TYPE = 45;
    public const OPERATION_NAME = 'renewal_of_subscription';

    /** @var AssetAmount */
    private $payout;
    /** @var ChainObject */
    private $author;
    /** @var array */
    private $coAuthors;
    /** @var ChainObject */
    private $buying;
    /** @var ChainObject */
    private $consumer;

    public function __construct()
    {
        parent::__construct();
        $this->payout = new AssetAmount();
    }

    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[payout][amount]' => 'payout.amount',
                '[payout][asset_id]' => 'payout.assetId',
                '[author]' => 'author',
                // TODO: coAuthors
//                '[co_authors]' => 'author',
                '[buying]' => 'buying',
                '[consumer]' => 'consumer',
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
     * @return AssetAmount
     */
    public function getPayout(): AssetAmount
    {
        return $this->payout;
    }

    /**
     * @param AssetAmount $payout
     * @return RenewalOfSubscription
     */
    public function setPayout(AssetAmount $payout): RenewalOfSubscription
    {
        $this->payout = $payout;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAuthor(): ChainObject
    {
        return $this->author;
    }

    /**
     * @param ChainObject | string $author
     * @return RenewalOfSubscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAuthor($author): RenewalOfSubscription
    {
        if (is_string($author)) {
            $author = new ChainObject($author);
        }

        $this->author = $author;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoAuthors(): array
    {
        return $this->coAuthors;
    }

    /**
     * @param array $coAuthors
     * @return RenewalOfSubscription
     */
    public function setCoAuthors(array $coAuthors): RenewalOfSubscription
    {
        $this->coAuthors = $coAuthors;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getBuying(): ChainObject
    {
        return $this->buying;
    }

    /**
     * @param ChainObject | string $buying
     * @return RenewalOfSubscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setBuying($buying): RenewalOfSubscription
    {
        if (is_string($buying)) {
            $buying = new ChainObject($buying);
        }

        $this->buying = $buying;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject | string $consumer
     * @return RenewalOfSubscription
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setConsumer($consumer): RenewalOfSubscription
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }

        $this->consumer = $consumer;

        return $this;
    }

}