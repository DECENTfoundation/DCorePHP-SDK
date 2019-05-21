<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;

class AssetIssueOperation extends BaseOperation
{
    public const OPERATION_TYPE = 4;
    public const OPERATION_NAME = 'asset_issue';

    /** @var ChainObject */
    private $issuer;
    /** @var AssetAmount */
    private $assetToIssue;
    /** @var ChainObject */
    private $issueToAccount;
    /** @var Memo */
    private $memo;

    /**
     * AssetIssueOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->assetToIssue = new AssetAmount();
    }


    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject | string $issuer
     * @return AssetIssueOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setIssuer($issuer): AssetIssueOperation
    {
        if (is_string($issuer)) {
            $issuer = new ChainObject($issuer);
        }
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getAssetToIssue(): AssetAmount
    {
        return $this->assetToIssue;
    }

    /**
     * @param AssetAmount $assetToIssue
     * @return AssetIssueOperation
     */
    public function setAssetToIssue(AssetAmount $assetToIssue): AssetIssueOperation
    {
        $this->assetToIssue = $assetToIssue;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getIssueToAccount(): ChainObject
    {
        return $this->issueToAccount;
    }

    /**
     * @param ChainObject | string $issueToAccount
     * @return AssetIssueOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setIssueToAccount($issueToAccount): AssetIssueOperation
    {
        if (is_string($issueToAccount)) {
            $issueToAccount = new ChainObject($issueToAccount);
        }
        $this->issueToAccount = $issueToAccount;

        return $this;
    }

    /**
     * @return Memo | null
     */
    public function getMemo(): ?Memo
    {
        return $this->memo;
    }

    /**
     * @param Memo $memo
     * @return AssetIssueOperation
     */
    public function setMemo(?Memo $memo): AssetIssueOperation
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'issuer' => $this->getIssuer()->getId(),
                'asset_to_issue' => $this->getAssetToIssue()->toArray(),
                'issue_to_account' => $this->getIssueToAccount()->getId(),
                'memo' => $this->getMemo() ? $this->getMemo()->toArray() : null
            ],
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getIssuer()->toBytes(),
            $this->getAssetToIssue()->toBytes(),
            $this->getIssueToAccount()->toBytes(),
            $this->getMemo() ? $this->getMemo()->toBytes() : '00',
            // TODO: Extensions Array
            $this->getExtensions() ? '01' : '00'
        ]);
    }
}