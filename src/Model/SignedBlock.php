<?php

namespace DCorePHP\Model;

class SignedBlock
{
    /** @var string */
    private $previous;
    /** @var \DateTime */
    private $timestamp;
    /** @var ChainObject */
    private $miner;
    /** @var string */
    private $transactionMerkleRoot;
    /** @var string */
    private $minerSignature;
    /** @var ProcessedTransaction[] */
    private $transactions;
    /** @var array */
    private $extensions;

    /**
     * @return string
     */
    public function getPrevious(): string
    {
        return $this->previous;
    }

    /**
     * @param string $previous
     * @return SignedBlock
     */
    public function setPrevious(string $previous): SignedBlock
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
     * @return SignedBlock
     * @throws \Exception
     */
    public function setTimestamp($timestamp): SignedBlock
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
     * @return SignedBlock
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setMiner($miner): SignedBlock
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
     * @return SignedBlock
     */
    public function setTransactionMerkleRoot(string $transactionMerkleRoot): SignedBlock
    {
        $this->transactionMerkleRoot = $transactionMerkleRoot;

        return $this;
    }

    /**
     * @return string
     */
    public function getMinerSignature(): string
    {
        return $this->minerSignature;
    }

    /**
     * @param string $minerSignature
     * @return SignedBlock
     */
    public function setMinerSignature(string $minerSignature): SignedBlock
    {
        $this->minerSignature = $minerSignature;

        return $this;
    }

    /**
     * @return ProcessedTransaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param ProcessedTransaction[] $transactions
     * @return SignedBlock
     */
    public function setTransactions(array $transactions): SignedBlock
    {
        $this->transactions = $transactions;

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
     * @return SignedBlock
     */
    public function setExtensions(array $extensions): SignedBlock
    {
        $this->extensions = $extensions;

        return $this;
    }
}