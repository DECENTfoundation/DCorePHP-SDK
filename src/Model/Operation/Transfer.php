<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Memo;

class Transfer extends BaseOperation
{
    public const OPERATION_TYPE = 0;
    public const OPERATION_NAME = 'transfer';

    /** @var string */
    private $from;
    /** @var string */
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
                '[memo][from]' => 'memo.from',
                '[memo][to]' => 'memo.to',
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
        }
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return Transfer
     */
    public function setFrom(string $from): Transfer
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return Transfer
     */
    public function setTo(string $to): Transfer
    {
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
     * @return Transfer
     */
    public function setAmount(AssetAmount $amount): Transfer
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
     * @return Transfer
     */
    public function setMemo(Memo $memo): Transfer
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
                    'from' => $this->getFrom(),
                    'to' => $this->getTo(),
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
    protected function getFromBytes(): string
    {
        [$space, $type, $instance] = explode('.', $this->getFrom());

        return dechex($instance);
    }

    /**
     * @return string
     */
    protected function getToBytes(): string
    {
        [$space, $type, $instance] = explode('.', $this->getTo());

        return dechex($instance);
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getFrom(),
            $this->getToBytes(),
            $this->getAmount()->toBytes(),
            $this->getMemo()->isEmpty() ? str_pad('', 33, '00') : $this->getMemo()->toBytes(),
            '00',
        ]);
    }
}
