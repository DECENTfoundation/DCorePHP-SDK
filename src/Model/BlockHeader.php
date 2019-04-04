<?php

namespace DCorePHP\Model;

class BlockHeader
{
    /** @var string */
    private $previous;
    /** @var \DateTime */
    private $timestamp;
    /** @var ChainObject */
    private $miner;
    /** @var string */
    private $transactionMerkleRoot;

    /**
     * @return string
     */
    public function getPrevious(): string
    {
        return $this->previous;
    }

    /**
     * @param string $previous
     * @return BlockHeader
     */
    public function setPrevious(string $previous): BlockHeader
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime|string $timestamp
     * @return BlockHeader
     * @throws \Exception
     */
    public function setTimestamp($timestamp): BlockHeader
    {
        $this->timestamp = $timestamp instanceof \DateTime ? $timestamp : new \DateTime($timestamp);

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getMiner(): ChainObject
    {
        return $this->miner;
    }

    /**
     * @param ChainObject|string $miner
     * @return BlockHeader
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setMiner($miner): BlockHeader
    {
        if (is_string($miner)) {
            $miner = new ChainObject($miner);
        }
        $this->miner = $miner;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionMerkleRoot(): string
    {
        return $this->transactionMerkleRoot;
    }

    /**
     * @param string $transactionMerkleRoot
     * @return BlockHeader
     */
    public function setTransactionMerkleRoot(string $transactionMerkleRoot): BlockHeader
    {
        $this->transactionMerkleRoot = $transactionMerkleRoot;

        return $this;
    }
}