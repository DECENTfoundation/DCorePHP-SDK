<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class FinishBuyingOperation extends BaseOperation
{
    public const OPERATION_TYPE = 45;
    public const OPERATION_NAME = 'finish_buying';

    /** @var AssetAmount */
    private $payout;
    /** @var ChainObject */
    private $author;
    /** @var ChainObject */
    private $seeder;

    public function __construct()
    {
        parent::__construct();
        $this->payout = new AssetAmount();
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
                '[payout][amount]' => 'payout.amount',
                '[payout][asset_id]' => 'payout.assetId',
                '[author]' => 'author',
                '[seeder]' => 'seeder',
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
    public function getPayout(): ?AssetAmount
    {
        return $this->payout;
    }

    /**
     * @param AssetAmount $payout
     * @return FinishBuyingOperation
     */
    public function setPayout(AssetAmount $payout): FinishBuyingOperation
    {
        $this->payout = $payout;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAuthor(): ?ChainObject
    {
        return $this->author;
    }

    /**
     * @param ChainObject|string $author
     * @return FinishBuyingOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAuthor($author): FinishBuyingOperation
    {
        if (is_string($author)) {
            $author = new ChainObject($author);
        }

        $this->author = $author;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getSeeder(): ?ChainObject
    {
        return $this->seeder;
    }

    /**
     * @param ChainObject|string $seeder
     * @return FinishBuyingOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSeeder($seeder): FinishBuyingOperation
    {
        if (is_string($seeder)) {
            $seeder = new ChainObject($seeder);
        }

        $this->seeder = $seeder;
        return $this;
    }
}