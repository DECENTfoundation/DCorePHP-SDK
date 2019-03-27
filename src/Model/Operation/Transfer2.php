<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Crypto\Address;

class Transfer2 extends BaseOperation
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
     * @throws \Exception
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
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
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
     * @return Transfer2
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setFrom($from): Transfer2
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
     * @return Transfer2
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setTo($to): Transfer2
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
     * @return Transfer2
     */
    public function setAmount(AssetAmount $amount): Transfer2
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Memo
     */
    public function getMemo(): Memo
    {
        return $this->memo;
    }

    /**
     * @param Memo $memo
     * @return Transfer2
     */
    public function setMemo(Memo $memo): Transfer2
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
                    'extensions' => [],
                    'from' => $this->getFrom()->getId(),
                    'to' => $this->getTo()->getId(),
                    'amount' => $this->getAmount()->toArray(),
                    'fee' => $this->getFee()->toArray(),
                ],
                !$this->getMemo()->isEmpty() ? ['memo' => $this->getMemo()->toArray()] : []
            ),
        ];
    }

    /**
     * @return string
     */
    protected function getToObjectTypeIdBytes(): string
    {
        [$space, $type, $instance] = explode('.', $this->getTo()->getId());

        return str_pad(strrev(dechex(($space << 56) | ($type << 48))), 16, '0', STR_PAD_LEFT) | str_pad(dechex($instance), 16, '0', STR_PAD_RIGHT);
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
            $this->getFrom()->toBytes(),
            $this->getToObjectTypeIdBytes(),
            $this->getAmount()->toBytes(),
            $this->getMemo()->isEmpty() ? str_pad('', 33, '00') : $this->getMemo()->toBytes(),
            '00',
        ]);
    }
}
