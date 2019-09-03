<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\VarInt;
use Exception;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class TransferOperation extends BaseOperation
{
    public const OPERATION_TYPE = 39;
    public const OPERATION_NAME = 'transfer2';

    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var AssetAmount */
    private $amount;
    /** @var Memo */
    private $memo;

    public function __construct()
    {
        parent::__construct();
        $this->amount = new AssetAmount();
        $this->memo = new Memo();
    }

    /**
     * @param array $rawOperation
     * @throws Exception
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[from]' => 'from',
                '[to]' => 'to',
                '[amount][amount]' => 'amount.amount',
                '[amount][asset_id]' => 'amount.assetId',
                '[memo][nonce]' => 'memo.nonce',
                '[memo][message]' => 'memo.message',
                '[extensions]' => 'extensions',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (NoSuchPropertyException $exception) {
                // skip
            }
            if ($rawOperation['memo']) {
                $this->getMemo()->setFrom(Address::decodeCheckNull($rawOperation['memo']['from']));
                $this->getMemo()->setTo(Address::decodeCheckNull($rawOperation['memo']['to']));
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getFrom(): ChainObject
    {
        return $this->from;
    }

    /**
     * @param ChainObject|string $from
     *
     * @return TransferOperation
     * @throws ValidationException
     */
    public function setFrom($from): TransferOperation
    {
        if (is_string($from)) {
            $from = new ChainObject($from);
        }
        $this->from = $from;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getTo(): ChainObject
    {
        return $this->to;
    }

    /**
     * @param ChainObject|string $to
     *
     * @return TransferOperation
     * @throws ValidationException
     */
    public function setTo($to): TransferOperation
    {
        if (is_string($to)) {
            $to = new ChainObject($to);
        }
        $this->to = $to;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getAmount(): AssetAmount
    {
        return $this->amount;
    }

    /**
     * @param AssetAmount $amount
     *
     * @return TransferOperation
     */
    public function setAmount(AssetAmount $amount): TransferOperation
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Memo
     */
    public function getMemo(): ?Memo
    {
        return $this->memo;
    }

    /**
     * @param Memo $memo
     *
     * @return TransferOperation
     */
    public function setMemo(?Memo $memo): TransferOperation
    {
        $this->memo = $memo;
        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            array_merge(
                [
                    'from' => $this->getFrom()->getId(),
                    'to' => $this->getTo()->getId(),
                    'amount' => $this->getAmount()->toArray(),
                    'fee' => $this->getFee()->toArray(),
                ],
                ['memo' => $this->getMemo() ? $this->getMemo()->toArray() : null]
            ),
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
            $this->getFrom()->toBytes(),
            $this->getTo()->toObjectTypeIdBytes(),
            $this->getAmount()->toBytes(),
            $this->getMemo() ? $this->getMemo()->toBytes() : '00',
            $this->getExtensions() ?
                VarInt::encodeDecToHex(sizeof($this->getExtensions()))
                . '' // TODO array_map each element toBytes()
                : '00'
        ]);
    }
}
