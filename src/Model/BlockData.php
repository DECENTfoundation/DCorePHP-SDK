<?php

namespace DCorePHP\Model;

use DCorePHP\Utils\Math;

class BlockData
{
    /** @var integer */
    private $refBlockNum;
    /** @var string */
    private $refBlockPrefix;
    /** @var \DateTime */
    private $expiration;

    /**
     * BlockData constructor.
     * @param int $refBlockNum
     * @param string $refBlockPrefix
     * @param \DateTime|string $expiration
     * @throws \Exception
     */
    public function __construct(int $refBlockNum, string $refBlockPrefix, $expiration)
    {
        $this->refBlockNum = $refBlockNum;
        $this->refBlockPrefix = $refBlockPrefix;
        $this->setExpiration($expiration);
    }

    /**
     * BlockData constructor
     *
     * @param string $headBlockNumber
     * @param string $headBlockId
     * @param \DateTime|string $expiration
     * @return BlockData
     * @throws \Exception
     */
    public static function fromHeadBlock(string $headBlockNumber, string $headBlockId, $expiration): self
    {
        $refBlockNum = (int)$headBlockNumber & 0xFFFF;

        $newHeadBlockId = substr($headBlockId, 8, 8);
        $newHeadBlockId = str_split($newHeadBlockId, 2);
        $newHeadBlockId = array_reverse($newHeadBlockId);
        $newHeadBlockId = implode('', $newHeadBlockId);
        $newHeadBlockId = hexdec($newHeadBlockId);

        $refBlockPrefix = $newHeadBlockId;
        return new self($refBlockNum, $refBlockPrefix, $expiration);
    }


    /**
     * @param DynamicGlobalProps $props
     * @param string $expiration
     * @return BlockData
     * @throws \Exception
     */
    public static function fromDynamicGlobalProps(DynamicGlobalProps $props, string $expiration): self
    {
        return self::fromHeadBlock($props->getHeadBlockNumber(), $props->getHeadBlockId(), $props->getTime()->add(new \DateInterval("PT${expiration}S")));
    }

    /**
     * @return BlockData
     */
    public function increment(): BlockData
    {
        $this->getExpiration()->modify('+1 second');

        return $this;
    }

    /**
     * @return int
     */
    public function getRefBlockNum(): int
    {
        return $this->refBlockNum;
    }

    /**
     * @param int $refBlockNum
     * @return BlockData
     */
    public function setRefBlockNum(int $refBlockNum): BlockData
    {
        $this->refBlockNum = $refBlockNum;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefBlockPrefix(): string
    {
        return $this->refBlockPrefix;
    }

    /**
     * @param string $refBlockPrefix
     * @return BlockData
     */
    public function setRefBlockPrefix(string $refBlockPrefix): BlockData
    {
        $this->refBlockPrefix = $refBlockPrefix;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|string $expiration
     * @return BlockData
     * @throws \Exception
     */
    public function setExpiration($expiration): BlockData
    {
        $this->expiration = $expiration instanceof \DateTime ? $expiration : new \DateTime($expiration);

        return $this;
    }

    public function toBytes(): string
    {
        return implode('', [ // block data bytes
            Math::getInt16Reversed($this->getRefBlockNum()),
            Math::getInt32Reversed($this->getRefBlockPrefix()),
            Math::getInt32Reversed($this->getExpiration()->getTimestamp()),
        ]);
    }
}